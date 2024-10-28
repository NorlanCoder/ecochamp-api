<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\FriendRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FriendRequestController extends Controller
{

    /**
     * Envoi de request d'ami 
     */
    public function sendFriendRequest(Request $request)
    {
        $request->validate([
            'receiverId' => 'required'
        ]);

        if (FriendRequest::where('sender_id', auth()->id())->where('receiver_id', $request->receiverId)->exists()) 
        {
            return response()->json(
                [
                    'success' => true,
                    'code' => 400,
                    'message' => 'Demande déjà envoyée.',
            ]);
        }

        FriendRequest::create([
            'sender_id' => auth()->id(),
            'receiver_id' => $request->receiverId,
            'status' => 'pending',
        ]);

        return response()->json(
            [
                'success' => true,
                'code' => 200,
                'message' => 'Demande envoyée.'
            ]);
    }

    /**
     * Repondre à une request d'ami 
     */
    public function respondToFriendRequest(Request $request)
    {
        $request->validate([
            'requestId' => 'required',
            'action' => 'required|in:accept,decline'
        ]);
        $requestId = $request->requestId;
        $action = $request->action;

        $friendRequest = FriendRequest::findOrFail($requestId);

        if ($action == 'accept') {
            $friendRequest->status = 'accepted';
        } elseif ($action == 'decline') {
            $friendRequest->status = 'declined';
        }

        $friendRequest->save();
        return response()->json(
            [
                'success' => true,
                'code' => 200,
                'message' => 'Réponse enregistrée.'
            ]);
    }

    /**
     * Liste des reponse d'ami en attente
     */
    public function getFriendRequests()
    {
        $user = User::where('id', Auth::user()->id)->first();
        $requests = $user->receivedFriendRequests()->where('status', 'pending')->get();
        return response()->json(
            [
                'success' => true,
                'code' => 200,
                'message' => 'liste amis en attente',
                'data' => $requests
            ]);
    }

    /**
     * Liste des amis 
     */
    public function getFriendsList()
    {
        $friends = FriendRequest::where(function($query) {
            $query->where('sender_id', auth()->id())->orWhere('receiver_id', auth()->id());
        })->where('status', 'accepted')->get();

        return response()->json(
            [
                'success' => true,
                'code' => 200,
                'message' => 'liste des amis',
                'data' => $friends
            ]);
    }

    
}
