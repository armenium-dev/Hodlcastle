<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ResetPassword extends Notification
{
    use Queueable;

    protected $token;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($token)
    {
        $this->token = $token;
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
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->from(config('mail.email_noreply'), config('mail.email_noreply'))
                    ->subject('Password Reset')
                    ->greeting($notifiable->name . ', ')
                    ->line('Someone requested that the password for your account be reset.')
                    ->action('Reset My Password', url(config('app.url').route('password.reset', $this->token, false)))
                    ->line('Copyable link: ' . url(config('app.url').route('password.reset', $this->token, false)))
                    ->line('This link is good for 2 hours and can only be used once.')
                    ->line('If you didn\'t request this, you can ignore this email or let us know. Your password won\'t change until you create a new password.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
