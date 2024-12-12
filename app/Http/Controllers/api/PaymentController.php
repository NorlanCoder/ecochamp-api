<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\PostPayment;
use App\Models\User;
use Illuminate\Http\Request;
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
                'Authorization' => 'Bearer ' . $cle , //sk_sandbox_xD-eXmwB90F2Ih6PWATQKnLb',
                'Content-Type' => 'application/json',
            ])->get('https://sandbox-api.fedapay.com/v1/transactions/'.$request->id);
            
            $data = $response->json()["v1/transaction"];

            // Gestion de la réponse
            if (!$response->successful()) {
                return response()->json(['error' => 'Erreur lors de la récupération de la transaction'], 500);
            }

            return response()->json([
                'data' => $data,
                'request' => $request,
            ]);

            $post = Post::find($request('post_id'));
            $donator = User::find($request('user_id'));
            $action = $request('type');
            $status = User::find($data['status']);

           
            $payement = PostPayment::create([
                'donator_id' => $donator->id,
                'amount' => $data['amount'],
                'free' => ($property->user->free * $data['amount'])/100,
                'type' => 'fedapay',
                // 'transaction' => $data,
                'reference' => $data['reference'],
                'user_id' => $user->id,
                'property_id' => $property->id,
            ]);

            DB::commit();

            return 'approuved';

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(["errors" => $e->getMessage(), "status" => 500], 500);
        }
    }
}
