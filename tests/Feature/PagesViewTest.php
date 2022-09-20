<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PagesViewTest extends TestCase
{

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicTest()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertViewIs('home');
    }

    public function testCampaigns()
    {
        $campaign = factory('App\Models\Campaign')->create();

        $response = $this->get('/campaigns');

        $response->assertStatus(200);
        $response->assertViewIs('campaigns.index');
        $response->assertSee($campaign->name);
    }

    public function testDomains()
    {
        $domain = factory('App\Models\Domain')->create();
        $response = $this->get('/domains');

        $response->assertStatus(200);
        $response->assertViewIs('domains.index');
        $response->assertSee($domain->name);
    }

    public function testEmailTemplates()
    {
        $et = factory('App\Models\EmailTemplate')->create();
        $response = $this->get('/emailTemplates');

        $response->assertStatus(200);
        $response->assertViewIs('email_templates.index');
        $response->assertSee($et->name);
    }

    public function testEvents()
    {
        $event = factory('App\Models\Event')->create();
        $response = $this->get('/events');

        $response->assertStatus(200);
        $response->assertViewIs('events.index');
        $response->assertSee($event->name);
    }

    public function testGroups()
    {
        $group = factory('App\Models\Group')->create();
        $response = $this->get('/groups');

        $response->assertStatus(200);
        $response->assertViewIs('groups.index');
        $response->assertSee($group->name);
    }

    public function testLandings()
    {
        $landing = factory('App\Models\Landing')->create();
        $response = $this->get('/landings');

        $response->assertStatus(200);
        $response->assertViewIs('landings.index');
        $response->assertSee($landing->name);
    }

    public function testPermissions()
    {
        $perm = factory('App\Permission')->create();
        $response = $this->get('/permissions');

        $response->assertStatus(200);
        $response->assertViewIs('permissions.index');
        $response->assertSee($perm->name);
    }

//    public function testRecipients()
//    {
//        $response = $this->get('/recipients');
//
//        $response->assertStatus(200);
//        $response->assertViewIs('recipients.index');
//    }

    public function testResults()
    {
        $result = factory('App\Models\Result')->create();
        $response = $this->get('/results');

        $response->assertStatus(200);
        $response->assertViewIs('results.index');
        $response->assertSee($result->name);
    }

    public function testRoles()
    {
        $role = factory('App\Role')->create();
        $response = $this->get('/roles');

        $response->assertStatus(200);
        $response->assertViewIs('roles.index');
        $response->assertSee($role->name);
    }

    public function testSupergroups()
    {
        $model = factory('App\Models\Supergroup')->create();
        $response = $this->get('/supergroups');

        $response->assertStatus(200);
        $response->assertViewIs('supergroups.index');
        $response->assertSee($model->name);
    }

    public function testUsers()
    {
        $campaign = factory('App\Models\Campaign')->create();
        $response = $this->get('/users');

        $response->assertStatus(200);
        $response->assertViewIs('users.index');
    }
}
