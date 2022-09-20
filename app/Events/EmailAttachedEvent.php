<?php
namespace App\Events;

use Illuminate\Queue\SerializesModels;
use App\Models\SentAttachEmails;

class EmailAttachedEvent
{
    use SerializesModels;

    public $sent_email;

    /**
     * Create a new event instance.
     *
     * @param  sent_email  $sent_email
     * @return void
     */
    public function __construct(SentAttachEmails $sent_email)
    {
        $this->sent_email = $sent_email;
    }
}