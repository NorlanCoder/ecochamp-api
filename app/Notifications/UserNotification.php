<?php

namespace App\Notifications;

use App\Enums\NotificationType;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;


class UserNotification extends Notification
{
    use Queueable;
    private NotificationType $type; 
    private string $message;
    private string $username;
    private string $post_id;

    /**
     * Create a new notification instance.
     */
    public function __construct(NotificationType $type, $message, $username, $post_id)
    {
        $this->type = $type;
        $this->message = $message;
        $this->username = $username;
        $this->post_id = $post_id;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    // public function toMail(object $notifiable): MailMessage
    // {
    //     return (new MailMessage)
    //                 ->line('The introduction to the notification.')
    //                 ->action('Notification Action', url('/'))
    //                 ->line('Thank you for using our application!');
    // }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => $this->type->value,
            'username' => $this->username,
            'message' => $this->message,
            'post_id' => $this->post_id,
        ];
    }
}
