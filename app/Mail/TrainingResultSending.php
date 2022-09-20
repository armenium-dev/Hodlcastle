<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Str;

class TrainingResultSending extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The demo object instance.
     *
     * @var Demo
     */
    public $data;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $this
            ->subject('Result Security Awareness Training E-mail')
            ->from(config('mail.email_noreply'))
            ->markdown('emails.training_result.sending');

        $this->withSwiftMessage(function ($message) {
            $message->getHeaders()->addTextHeader('X-No-Track', Str::random(10));
        });

        return $this;
    }
}
