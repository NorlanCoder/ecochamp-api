<?php

namespace App\Repository;

use App\Models\Message;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

class ConversationsRepository
{


    /**
     * @var User
     */
    private $user;

    /**
     * @var Message
     */
    private $message;

    public function __construct(User $user, Message $message)
    {
        $this->user = $user;
        $this->message = $message;
    }

    public function getConversation(int $userId){
        $conversations = $this->user->newQuery()
        ->select('id', 'firstname')
        ->where('id', '!=', $userId)
        ->get();
        // $unread = $this->unreadCount($userId);
        // foreach($conversations as $conversation){
        //     if( isset($unread[$conversation->id]))
        //     {
        //         $conversation->unread = $unread[$conversation->id];
        //     }
        //     else{
        //         $conversation->unread = 0;
        //     }
        // }
        return $conversations;
    }

    public function create_message(string $content, int $from, int $to){
        return $this->message->newQuery()->create([
            "content" => $content,
            "from_id" => $from,
            "to_id" => $to,
        ]);
    }

    public function getMessageFor(int $from, int $to): Builder
    {
        return $this->message->newQuery()
        ->whereRaw("((from_id = $from And to_id = $to) OR (from_id = $to AND to_id = $from))")
        ->orderBy('created_at', 'DESC');
    }

    /**
     * le nombre des conversation non lue
     * @param $userId
     */
    public function unreadCount(int $userId){
        $this->message->newQuery()
        ->where('to_id', $userId)
        ->groupBy('from_id')
        ->selectRaw('from_id, Count(id) as count')
        ->whereRaw('red_at IS NULL')
        ->get()
        ->pluck('count', 'from_id');
    }
}