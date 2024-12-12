<?php

namespace App\Http\Controllers\api;

use App\Enums\PostType;
use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Post;
use App\Models\PostActionUser;
use App\Models\PostPayment;
use App\Models\PostReaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StatisticController extends Controller
{
    /**
     * Mes actions(mes alerte, mes evenement, mes participation, ms mention jaime et mes finacemment)
     */
    public function index()
    {
        $mesAlert = Post::where('user_id', Auth::id())
            ->where('type', PostType::Alerte)
            ->count();
        $mesEvennement = Post::where('user_id', Auth::id())
            ->where('type', PostType::Evennement)
            ->count();
        $participation = PostActionUser::where('user_id', Auth::id())
            ->get()
            ->unique('post_action_id')
            ->count();
        $reaction = PostReaction::where('user_id', Auth::id())
            ->get()
            ->unique('post_id')
            ->count();
        $account = Account::where('user_id', Auth::id())->first();
        $mesdon = PostPayment::where('donator_id', Auth::id())->count();
        $mesdon = PostPayment::where('donator_id', Auth::id())->count();

        $post_ids = Post::where('user_id', Auth::id())->get()->pluck('id');
        $messoutiens = PostPayment::whereIn('post_id', $post_ids)->count();

          
        $list = [
            'mesAlerts' => $mesAlert,
            'mesEvennements' => $mesEvennement,
            'participations' => $participation,
            'reactions' => $reaction,
            'mesdons' => $mesdon,
            'messoutiens' => $messoutiens,
            'account' => $account
        ];

        return response()->json(
           [   
               'code' =>200,
               'success' => true,
               'data' => $list,
           ]
       );
    }


    /**
     * Historique des donations faites
     * 
     * @return \Illuminate\Http\Response
     * 
     */
    public function donFait(Request $request){

        $dons = PostPayment::where('donator_id', Auth::id());

        $data = [
            'solde_dons' => $dons->sum('amount'),
            'list_dons' => $dons->get()
        ];

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }


    /**
     * Historique des donations reçus
     * 
     * @return \Illuminate\Http\Response
     * 
     */
    public function donRecu(Request $request){ 

        $post_ids = Post::where('user_id', Auth::id())->get()->pluck('id');
        $dons = PostPayment::whereIn('post_id', $post_ids);
        $account = Account::where('user_id', Auth::id())->first();

        $data = [
            'solde' => $account->solde ?? 0,
            'solde_dons' => $dons->sum('amount'),
            'list_dons' => $dons->get()
        ];
        
        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }
}
