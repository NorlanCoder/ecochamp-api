<?php

namespace App\Http\Controllers\api\Chat;

use App\Events\NewMessage;
use App\Http\Controllers\Controller;
use App\Http\Requests\Chat\StoreMessageRequest;
use App\Models\Chat;
use App\Models\Message;
use App\Models\User;
use App\Repository\ConversationsRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ConversationController extends Controller
{

    /**
     * Chat Create
     **
     * Cette route est à utiliser pour creation d'une nouvelle conversation
     *
     *
     */
    public function createChat(Request $request){

        $user = Auth::user();

        $request->validate([
            'to_id' => 'required',
            'content' => 'required',
        ]);

        $chat = Chat::where(function($query) use ($user, $request) {
                $query->where('sender_id', $user->id)
                    ->where('receiver_id', $request->to_id);
            })
            ->orWhere(function($query) use ($user, $request) {
                $query->where('sender_id', $request->to_id)
                    ->where('receiver_id', $user->id);
            })
            ->first();
        
        if(!$chat){
            $chat = Chat::create([
                'sender_id' => $user->id,
                'receiver_id' => $request->to_id,
            ]);
            $message = Message::create([
                'chat_id' => $chat->id,
                'from_id' => $user->id,
                'to_id' => $request->to_id,
                'content' => $request->content,
            ]);

            $message = Message::where('id', $message->id)
                    ->with('from')
                    ->with('to')->first();

                $tab_chat = [
                    'id' => $chat->id,
                    'from_id' => $message->from_id,
                    'to_id' => $message->to_id,
                    'from' => User::where('id', $message->from_id)->first(),
                    'to' =>  User::where('id', $message->to_id)->first(),
                    'lastmessage' => Message::where('chat_id', $chat->id)->orderBy('id','desc')->limit(1)->get(),
                    'count_message' => Message::where('chat_id', $chat->id)
                                ->where('to_id', $request->to_id)
                                ->where('read_at', null)
                                ->count(),
                ];
            
            // event(new Discussion($tab_chat));

            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => 'message',
                'data' => $message,
            ]);
        }

        $message = Message::create([
            'chat_id' => $chat->id,
            'from_id' => $user->id,
            'to_id' => $request->to_id,
            'content' => $request->content,
        ]);

        return response()->json([
            'success' => true,
            'code' => 200,
            'message' => 'message ',
            'data' => $message,
        ]);
        
    }


    /**
     * Chat Exist
     **
     * Vérification si une conversation exist.
     */
    public function chatExist(Request $request){
        $user = Auth::user();

        $request->validate([
            'to_id' => 'required',
        ]);

        $chat = Chat::where(function($query) use ($user, $request) {
                    $query->where('sender_id', $user->id)
                        ->where('receiver_id', $request->to_id);
                })
                ->orWhere(function($query) use ($user, $request) {
                    $query->where('sender_id', $request->to_id)
                        ->where('receiver_id', $user->id);
                })
                ->first();
        
        return response()->json([
            'success' => true,
            'code' => 200,
            'chat' => isset($chat),
            'data' => $chat,
        ]);
    }

    /**
     * list user conversation.
     */
    public function listConversations()
    {
        $user = Auth::user();

        $listchat = Chat::where(function($query) {
            $query->where('sender_id', auth()->id())
                  ->orWhere('receiver_id', auth()->id());
                })
                ->get();
            
        $listchat->transform(function($query) use ($user){
            $query->from = User::where('id', $query->sender_id)->first();
            $query->to = User::where('id', $query->receiver_id)->first();
            $query->lastmessage = Message::where('chat_id', $query->id)->orderBy('id','desc')->limit(1)->get();
            $query->count_message = Message::where('chat_id', $query->id)
                            ->where('to_id', $user->id)
                            ->where('read_at', null)
                            ->count();
            return $query;

        });

        return response()->json([
            'status' => 'success',
            'message' => 'list conversations',
            'code' => 200,
            'data' => $listchat,
        ]); 
    }

    /**
     * create send message.
     */
    public function sendMessage(StoreMessageRequest $request)
    {
        $user = Auth::user();

        $chat = Chat::where(function($query) use ($user, $request) {
                $query->where('sender_id', $user->id)
                    ->where('receiver_id', $request->to_id);
            })
            ->orWhere(function($query) use ($user, $request) {
                $query->where('sender_id', $request->to_id)
                    ->where('receiver_id', $user->id);
            })
            ->first();

        $message = Message::create([
            'content' => $request->content,
            'from_id' => $user->id,
            'to_id' => $request->to_id,
            'chat_id' => $chat->id
        ]);

        // event(new NewMessage($message));

        return response()->json([
            'status' => 'success',
            'message' => 'send message',
            'code' => 200,
            'data' => $message,
        ]); 
    }

    /**
     * get Message for
     */
    public function getMessageFor(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'chat_id' => 'required',
        ]);

        $listmessage = Message::where('chat_id', $request->chat_id)
                    ->where(function ($query) use ($user) {
                        $query->where('from_id', $user->id)
                        ->orWhere('to_id', $user->id);
                    })->get();
        $listmessage->transform(function($query){

            $query->from = User::where('id', $query->from_id)->first();
            $query->to = User::where('id', $query->to_id)->first();
            return $query;

        });
            
        $messages = Message::where('chat_id', $request->chat_id)
                ->where('to_id', $user->id)
                ->where('read_at', null)
                ->get();
        
        foreach($messages as $item){
            $item->read_at = now();
            $item->save();

            $tab_meg = [
                'id' => $item->id,
                'chat_id' => $item->chat_id,
                'from_id' => $item->from_id,
                'from' => User::where('id', $item->from_id)->first(),
                'to_id' => $request->to_id,
                'to' => User::where('id', $request->to_id)->first(),
                'content' => $item->content,
                'read_at' => $item->read_at,
                'created_at' => $item->created_at,
                'updated_at' => $item->updated_at
            ];
            
            // event(new ReadMessage($tab_meg));
        }            

        return response()->json([
            'status' => 'success',
            'message' => 'get message for',
            'code' => 200,
            'data' => null,
        ]); 
    }

    public function readMessage(Request $request){
        $user = Auth::user();

        $request->validate([
            'message_id' => 'required',
        ]);
        $message = Message::where('id', $request->message_id)
                ->where('to_id', $user->id)
                ->first();

        if($message){        
            $message->read_at = now();
            $message->save();

            $tab_meg = [
                'id' => $message->id,
                'chat_id' => $message->chat_id,
                'from_id' => $message->from_id,
                'from' => User::where('id', $message->from_id)->first(),
                'to_id' => $request->to_id,
                'to' => User::where('id', $request->to_id)->first(),
                'content' => $message->content,
                'read_at' => $message->read_at,
                'created_at' => $message->created_at,
                'updated_at' => $message->updated_at
            ];
            // event(new ReadMessage($tab_meg));
        }
    }
}
