<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Laravel\Firebase\Facades\Firebase;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class ParticipantNotification extends Notification
{
    use Queueable;

    private User $user;
    private User $participant;

    /**
     * Create a new notification instance.
     */
    public function __construct(User $user, User $participant)
    {
        $this->user = $user;
        $this->participant = $participant;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        try {
            $message = CloudMessage::new()
                        ->toToken($this->user->token_notify)
                        ->withNotification([
                            'title' => 'Notification de participation',
                            'body' => 'Vous avez un nouveau participant à votre événement.',
                        ]);
                        // ->withData([
                        //     'id' => $notification->id,
                        // ]);
            Firebase::messaging()->send($message);
        } catch (\Exception $e) {
            // Handle exceptions here
            Log::error('Error sending notification: ' . $e->getMessage());
            // Or throw a custom exception
            // throw new \RuntimeException('Failed to send notification', 0, $e);
        }
        
        return (new MailMessage)
            ->view('emails.interet', ['user' => $this->user, 'participant' => $this->participant]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
