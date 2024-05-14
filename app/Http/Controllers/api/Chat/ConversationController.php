<?php

namespace App\Http\Controllers\api\Chat;

use App\Events\NewMessage;
use App\Http\Controllers\Controller;
use App\Http\Requests\Chat\StoreMessageRequest;
use App\Repository\ConversationsRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ConversationController extends Controller
{
    protected $user;

    protected $coversRepo;

    public function __construct(ConversationsRepository $conversationsRepository)
    {
        $this->coversRepo = $conversationsRepository;
        $this->user = Auth::user();
    }


    /**
     * list user conversation.
     */
    public function listConversations()
    {
        $this->user = Auth::user();
        // dd($this->user);
        return response()->json([
            'status' => 'success',
            'message' => 'list conversations',
            'code' => '200',
            // 'dd' => $this->user,
            'data' => $this->coversRepo->getconversation($this->user->id),
        ]); 
    }

    /**
     * create send message.
     */
    public function sendMessage(StoreMessageRequest $request)
    {
        $this->user = Auth::user();
        $message = $this->coversRepo->create_message(
            $request->content,
            $this->user->id,
            $request->to_id
        );
        broadcast(new NewMessage($message));
        return response()->json([
            'status' => 'success',
            'message' => 'send message',
            'code' => '200',
            'data' => $message,
        ]); 
    }

    /**
     * get Message for
     */
    public function getMessageFor(Request $request)
    {
        $this->user = Auth::user();
        $request->validate([
            'user_id' => 'exists:App\Models\User,id'
        ]);
        return response()->json([
            'status' => 'success',
            'message' => 'get message for',
            'code' => '200',
            'data' => $this->coversRepo->getMessageFor($this->user->id, $request->user_id)->paginate(5),
        ]); 
    }

}
