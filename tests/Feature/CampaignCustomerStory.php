<?php namespace Tests\Feature;

use App\Models\EmailTemplate;
use App\Models\Landing;
use Tests\TestCase;
use App\Http\Controllers\CampaignController;
use App\Http\Requests\CreateCampaignRequest;

class CampaignCustomerStory extends TestCase
{

    public function setUp()
    {
        parent::setUp();

        $this->setUserCustomer();
    }


}