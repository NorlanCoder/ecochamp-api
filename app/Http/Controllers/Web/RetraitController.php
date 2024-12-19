<?php

namespace App\Http\Controllers\Web;

use App\Enums\RetraitEnum;
use App\Http\Controllers\Controller;
use App\Models\WithdrawRequest;
use App\Notifications\DemandeRetraitNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class RetraitController extends Controller
{
    /**
     * Display a listing of the resource. financement
     */
    public function index()
    {
        $user = Auth::user();
        $demandes =  WithdrawRequest::with('user')->paginate(10);
        return view('dashboard.retrait.demande', compact(['user', 'demandes']));
    }

    /**
     * Display a listing of the resource. demande de retrait
     */
    public function demande(WithdrawRequest $demande, Request $request)
    {
        $accept = $request->query('accept');

        if($accept == true){
            $demande->status = RetraitEnum::ACCEPT;
            $demande->save();
            Notification::send($demande->user, new DemandeRetraitNotification($demande->user));
        }
        if($accept == false){
            $demande->status = RetraitEnum::REJECT;
            $demande->save();
        }
        
        flash('Demande de retrait traitée avec succès.', 'success');
        return back();
    }
}
