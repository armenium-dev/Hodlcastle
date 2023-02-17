<?php

namespace App\Mail;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Domain;

class FailedLoginAttempts extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * The domain instance.
     *
     * @var Domain
     */
    public $user;

    /**
     * Create a new message instance.
     *
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Phishmanager Account Lockout Notice')
            ->from(config('mail.email_noreply'))
            ->markdown('emails.failed_login_attempts');
    }
}
