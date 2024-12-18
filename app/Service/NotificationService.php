<?php

namespace App\Service;

use App\Models\Post;
use App\Models\User;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Laravel\Firebase\Facades\Firebase;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    private Post $post;

    /**
     * Create a new notification instance.
     */
    public function __construct(Post $post)
    {
        $this->post = $post;
    }


    /**
     * Get push notification evennement
     */
    public function pushNotifyEvent()
    {
        $users = User::where('id', '!=', $this->post->user->id)->get();
        foreach($users as $user){

            try {
                $message = CloudMessage::new()
                            ->toToken($user->token_notify)
                            ->withNotification([
                                'title' => "Notification d'événement",
                                'body' => "Un nouvel événement vient d'être créé et recherche des participants.",
                            ])
                            ->withData([
                                'post_id' => $this->post->id,
                            ]);
                Firebase::messaging()->send($message);
            } catch (\Exception $e) {
                // Handle exceptions here
                Log::error('Error sending notification: ' . $e->getMessage());
            }

        }
        
    }

    /**
     * Get push notification alert
     */
    public function pushNotifyAlert()
    {
        $users = User::where('id', '!=', $this->post->user->id)->get();
        foreach($users as $user){

            try {
                $message = CloudMessage::new()
                            ->toToken($user->token_notify)
                            ->withNotification([
                                'title' => "Notification d'alerte",
                                'body' => "Une nouvelle alerte vient d'être créée",
                            ])
                            ->withData([
                                'post_id' => $this->post->id,
                            ]);
                Firebase::messaging()->send($message);
            } catch (\Exception $e) {
                // Handle exceptions here
                Log::error('Error sending notification: ' . $e->getMessage());
            }

        }
    }
}
