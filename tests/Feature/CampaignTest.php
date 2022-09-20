<?php namespace Tests\Feature;

use App\Models\Campaign;
use App\Models\EmailTemplate;
use Tests\TestCase;

class CampaignTest extends TestCase
{

    /** @test */
    public function userCanViewCampaign()
    {
        $campaign = factory(Campaign::class)->create();

        $response = $this->get('/campaigns/' . $campaign->id);

        $response->assertSee('Test Campaign');
    }

    /** @test */
    public function userCanCreateCampaign()
    {
        $response = $this->get('/campaigns/create');

        $response->assertStatus(200);
        $response->assertSee('Campaign');
    }
}