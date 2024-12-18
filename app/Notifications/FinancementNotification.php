<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Laravel\Firebase\Facades\Firebase;

class FinancementNotification extends Notification
{
    use Queueable;

    private User $user;
    private float $montant;
    /**
     * Create a new notification instance.
     */
    public function __construct(User $user, float $montant)
    {
        $this->user = $user;
        $this->montant = $montant;
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
                            'title' => 'Notification de financement',
                            'body' => 'Vous avez reçu un nouveau financement pour votre événement.',
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
            ->view('emails.financementrecu', ['user' => $this->user, 'montant' => $this->montant]);
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
