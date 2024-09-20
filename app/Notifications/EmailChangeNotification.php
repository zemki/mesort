<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\AnonymousNotifiable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\URL;

class EmailChangeNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * The user Email.
     *
     * @var string
     */
    protected $userId;

    /**
     * Create a new notification instance.
     */
    public function __construct(string $userId)
    {
        $this->userId = $userId;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return MailMessage
     */
    public function toMail(AnonymousNotifiable $notifiable)
    {
        return (new MailMessage())->markdown('email.emailreset', [
            'notifiable' => $notifiable,
            'route' => $this->verifyRoute($notifiable),
        ]);
    }

    /**
     * Returns the Reset URl to send in the Email.
     *
     * @return string
     */
    protected function verifyRoute(AnonymousNotifiable $notifiable)
    {
        return URL::temporarySignedRoute('verifyNewEmail', 60 * 60, [
            'user' => $this->userId,
            'email' => $notifiable->routes['mail'],
        ]);
    }
}
