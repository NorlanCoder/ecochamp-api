<?php

namespace App\Http\Controllers\api;

use App\Enums\RetraitEnum;
use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Post;
use App\Models\PostPayment;
use App\Models\User;
use App\Models\WithdrawRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class PaymentController extends Controller
{
    /**
     * soutien callback
     * 
     * @return \Illuminate\Http\Response
     * 
     */
    public function soutienCallback(Request $request){
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'post_id' => 'required|exists:posts,id',
            'type' => 'required',
            'mot_soutien' => 'required',
        ]);
        try {
            $cle = env('CLE_FEDAPAY');
            DB::beginTransaction();
            $response = Http::withHeaders([
                'Authorization' => 'Bearer sk_sandbox_xD-eXmwB90F2Ih6PWATQKnLb',
                'Content-Type' => 'application/json',
            ])->get('https://sandbox-api.fedapay.com/v1/transactions/'.$request->id);
            
            $data = $response->json()["v1/transaction"];

            // Gestion de la réponse
            if (!$response->successful()) {
                return response()->json(['error' => 'Erreur lors de la récupération de la transaction'], 500);
            }


            $post = Post::find($request->post_id);
            $donator = User::find($request->user_id);
            $action = $request->type;
            $donator_name = $data['custom_metadata']['fullname'];
           
            $payement = PostPayment::create([
                'donator_id' => $donator->id,
                'amount' => $data['amount'],
                'post_id' => $post->id,
                'action' => $action,
                'donator_name' => $donator_name,
                'mot_soutien' => $request->mot_soutien,                
                'status' => $data['status'],
                // 'reference' => $data['reference'],
                // 'transaction_id' => $request->id,
                ]);
            $account = Account::where('user_id', $post->user_id)->first();
            
            if($account){
                $account->solde += $data['amount'];
                $account->save();
            }else{
                $account = Account::create([
                    'user_id' => $post->user_id,
                    'solde' => $data['amount']
                ]); 
            }
            DB::commit();

            return response()->json([
                    'data' => $payement,
                    'success' => true,
                ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(["errors" => $e->getMessage(), "status" => 500], 500);
        }
    }   

    /**
     * Demande de retrait
     * 
     * @return \Illuminate\Http\Response
     * 
     */
    public function demandeRetrait(Request $request){
        $request->validate([
            'montant' => 'required',
            'phone' => 'required',
        ]);

        $retrait = WithdrawRequest::create([
            'montant' => $request->montant,
            'user_id' => Auth::id(),
            'status' => RetraitEnum::IN_PROGRESS,
            'phone' => $request->phone,
        ]);

        return response()->json([
            'messeage' => 'Demande en cours',
            'data' => $retrait,
            'success' => true,
        ]);
    }

}
