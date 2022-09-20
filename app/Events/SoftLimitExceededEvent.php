<?php namespace App\Events;

use App\Models\Company;
use Illuminate\Queue\SerializesModels;

class SoftLimitExceededEvent
{
    use SerializesModels;

    public $company;

    /**
     * Create a new event instance.
     *
     * @param  sent_email  $sent_email
     * @return void
     */
    public function __construct(Company $company)
    {
        $this->company = $company;
    }
}