<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\chat;
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

        $verify = FriendRequest::where('sender_id', auth()->id())->where('receiver_id', $request->receiverId)->exists()
                    || FriendRequest::where('receiver_id', auth()->id())->where('sender_id', $request->receiverId)->exists();
        if ($verify)
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
            $chat = chat::create([
                'sender_id' => $friendRequest->sender_id,
                'receiver_id' => $friendRequest->receiver_id,
            ]);
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
        $requests = $user->receivedFriendRequests()->where('status', 'pending')
            ->with('sender')
            ->with('receiver')
            // ->orderBy('fullname')
            ->get();
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
            $query->where('sender_id', auth()->id())
                  ->orWhere('receiver_id', auth()->id())
        ->distinct()
        ;
        })
        ->where('status', 'accepted')
        ->with(['sender', 'receiver'])
        ->distinct()
        ->get()
        ->map(function($friendRequest) {
            return $friendRequest->sender_id == auth()->id()
                ? $friendRequest->receiver
                : $friendRequest->sender;
        });

        $friends = $friends->transform(function($user) {
            $friendRequest = FriendRequest::where(function($query) use ($user) {
                                $query->where('sender_id', auth()->id())
                                    ->where('receiver_id', $user->id);
                            })
                            ->orWhere(function($query) use ($user) {
                                $query->where('receiver_id', auth()->id())
                                    ->where('sender_id', $user->id);
                            })
                            ->first();

            $user->friend_status = $friendRequest ? $friendRequest->status : null;

            return $user;
        });

        return response()->json(
            [
                'success' => true,
                'code' => 200,
                'message' => 'liste des amis',
                'data' => $friends
            ]);
    }

     /**
     * Liste sugestion  d'amis
     */
    public function getSuggestion()
    {
        $friend_ids = FriendRequest::where(function($query) {
            $query->where('sender_id', auth()->id())
                  ->orWhere('receiver_id', auth()->id());
            })
            ->whereIn('status', ['accepted', 'pending']) // Utilise whereIn pour les deux statuts
            ->get(['sender_id', 'receiver_id']) // Récupère les deux colonnes en même temps
            ->flatMap(function ($request) {
                return [$request->sender_id, $request->receiver_id];
            })
            ->unique()
            ->toArray();


            $suggfriends = User::where(function($query) {
                        $query->where('country', 'like', '%' . Auth::user()->country . '%')
                            ->orWhere('city', 'like', '%' . Auth::user()->city . '%');
                    })
                    ->whereNotIn('id', $friend_ids)
                    ->where('id', '!=', auth()->id())
                    ->get();

            $suggfriends = $suggfriends->transform(function($user) {
                    $friendRequest = FriendRequest::where(function($query) use ($user) {
                                        $query->where('sender_id', auth()->id())
                                            ->where('receiver_id', $user->id);
                                    })
                                    ->orWhere(function($query) use ($user) {
                                        $query->where('receiver_id', auth()->id())
                                            ->where('sender_id', $user->id);
                                    })
                                    ->first();

                    $user->friend_status = $friendRequest ? $friendRequest->status : null;

                    return $user;
                });



        return response()->json(
            [
                'success' => true,
                'code' => 200,
                'message' => 'liste des amis',
                'data' => $suggfriends
            ]);
    }


}
