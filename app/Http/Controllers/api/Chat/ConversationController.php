<?php

namespace App\Http\Controllers\Api\Chat;

use App\Events\NewMessage;
use App\Models\Conversation;
use App\Models\User;
use App\Repository\ConversationsRepository;
use Illuminate\Auth\AuthManager;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Chat\StoreMessageRequest;

class ConversationController extends Controller
{
/**
 * @var AuthManager
 */
private $auth;

/**
 * @var ConversationsRepository
 */
private $coversRepo;

public function __construct(ConversationsRepository $conversationsRepository, AuthManager $auth)
{
    $this->coversRepo = $conversationsRepository;
    $this->auth = $auth;
}
    /**
     * list user conversation.
     */
    public function listConversations()
    {
        return response()->json([
            'status' => 'success',
            'message' => 'list conversations',
            'code' => '200',
            'data' => $this->coversRepo->getconversation($this->auth->user()->id),
        ]); 
    }

    /**
     * create send message.
     */
    public function sendMessage(StoreMessageRequest $request)
    {
        $message = $this->coversRepo->create_message(
            $request->content,
            $this->auth->user()->id,
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
        return response()->json([
            'status' => 'success',
            'message' => 'get message for',
            'code' => '200',
            'data' => $this->coversRepo->getMessageFor($this->auth->user()->id, $request->user_id)->paginate(5),
        ]); 
    }

    /**
     * Display the specified resource.
     */
    public function show(Conversation $conversation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Conversation $conversation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Conversation $conversation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Conversation $conversation)
    {
        //
    }
}
