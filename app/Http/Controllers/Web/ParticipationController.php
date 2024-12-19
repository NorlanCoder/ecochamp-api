<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Action;
use App\Models\PostAction;
use App\Models\PostActionUser;
use App\Models\PostPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ParticipationController extends Controller
{
    /**
     * Display a listing of the resource. financement
     */
    public function financement()
    {
        $user = Auth::user();
        $financements =  PostPayment::with('user')->with('post')->paginate(10);
        return view('dashboard.participation.financement', compact(['user', 'financements']));
    }

    /**
     * Display a listing of the resource. participation
     */
    public function participation()
    {
        $user = Auth::user();
        $action = Action::where('label', 'participant')->first();
        $post_action_ids = DB::table('post_actions')->where('action_id', $action->id)->get()->pluck('id');
        $participants =  PostActionUser::whereIn('post_action_id', $post_action_ids)
            ->with('user')->paginate(10);

        return view('dashboard.participation.participant', compact(['user', 'participants']));
    }

    /**
     * Display a listing of the resource. benevolat
     */
    public function benevolat()
    {
        $user = Auth::user();
        $action = Action::where('label', 'benevole')->first();
        $post_action_ids = DB::table('post_actions')->where('action_id', $action->id)->get()->pluck('id');
        $benevoles =  PostActionUser::whereIn('post_action_id', $post_action_ids)
            ->with('user')->paginate(10);

        return view('dashboard.participation.benevolat', compact(['user', 'benevoles']));
    }
}
