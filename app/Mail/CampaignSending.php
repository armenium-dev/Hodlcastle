<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Domain;

class CampaignSending extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The domain instance.
     *
     * @var Domain
     */
    public $domain;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Domain $domain)
    {
        $this->domain = $domain;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from($this->domain->email)
            ->markdown('emails.campaign.sending');
    }
}
