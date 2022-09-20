<?php
namespace App\Events;

use Illuminate\Queue\SerializesModels;
use App\Models\SentSms;

class SmsEvent
{
    use SerializesModels;

    public $sent_sms;

    /**
     * Create a new event instance.
     *
     * @param  sent_sms  $sent_sms
     * @return void
     */
    public function __construct(SentSms $sent_sms)
    {
        $this->sent_sms = $sent_sms;
    }
}