<?php namespace Tests\Controllers;

use App;
use Tests\TestCase;
use Mockery as m;
use App\Models\Campaign;
use App\Models\Company;
use App\Http\Controllers\CampaignController;
use App\Http\Requests\CreateCampaignRequest;
use App\Http\Requests\UpdateCampaignRequest;
use App\Models\EmailTemplate;
use App\Models\Domain;
use App\Models\Landing;
use App\Models\Group;
use App\Models\Recipient;

class CampaignControllerTest extends TestCase
{
    const CAMPAIGN_NAME = 'Test user story';
    const EMAIL_TEMPLATE_NAME = 'URLtest';
    const DOMAIN_NAME = 'uefa-league.com';
    
    public $view_dir = 'campaigns';

    public $url = 'campaigns';

    public $var_single = 'campaign';

    public function setUp()
    {
        parent::setUp();

        $this->model = Campaign::has('schedule')->first();
    }

    public function testIndex()
    {
        $this->seedTestData();
        
        $response = $this->responseIndex();

        $response->assertOk();
        $response->assertViewIs($this->view_dir . '.index');
    }

    public function testCreate()
    {
        $response = $this->responseCreate();

        $response->assertOk();
        $response->assertViewIs($this->view_dir . '.create');
    }

    public function testStore()
    {
        \DB::table('campaigns')->whereName(self::CAMPAIGN_NAME)->delete();

        $response = $this->responseStore();
        $class = App::make(CampaignController::class);

        $request = new CreateCampaignRequest;
        $request->merge($this->modelTestAttrs());

        $return = $class->store($request);

        $this->model = Campaign::whereName(self::CAMPAIGN_NAME)->first();
        foreach ($this->model->recipients as $recipient) {
            $this->assertTrue((bool)$recipient->pivot->is_sent);
        }

        $response->assertStatus(302);
        $this->assertInstanceOf(\Illuminate\Http\RedirectResponse::class, $return);
    }

    public function testEdit()
    {
        $response = $this->responseEdit();

        $response->assertOk();
        $response->assertViewIs($this->view_dir . '.edit');
    }

    public function testUpdate()
    {
        $this->model = Campaign::whereName(self::CAMPAIGN_NAME)->first();

        $response = $this->responseUpdate();
        $class = App::make(CampaignController::class);

        $request = new UpdateCampaignRequest;
        $request->merge($this->modelTestAttrs());

        $return = $class->update($this->model->id, $request);

        $response->assertStatus(302);
        $this->assertInstanceOf(\Illuminate\Http\RedirectResponse::class, $return);
    }

    public function testTest()
    {

    }

    public function testEnd()
    {

    }

    public function testExport()
    {

    }

    public function testCustomerStory()
    {
        $campaign = Campaign::whereName(self::CAMPAIGN_NAME)->first();

        $email_template = $campaign->schedule->emailTemplate;

        $recipient = $campaign->recipients()->whereEmail(RECIPIENT_EMAIL)->first();

        $mailTemplate = $email_template->buildMailTemplate($recipient, $campaign);

        $build = $mailTemplate->build();
        $stringView = $email_template->is_html ? $build['html'] : $build['text'];
        $html = $stringView->render();

        $URL = self::DOMAIN_NAME . '?rid=' . $recipient->pivot->code;
        $URL_viewdata = $mailTemplate->viewData['.URL'];

        $format_check = (strpos($URL_viewdata, 'http://') === 0 ||
            strpos($URL_viewdata, 'https://') === 0);

        self::assertTrue($format_check);
        self::assertEquals($URL, strtr($URL_viewdata, ['http://' => '', 'https://' => '']));
        self::assertEquals('<html>Line 1: ' . $URL_viewdata . '<br>Line 2: <a href="' . $URL_viewdata . '">I like turtles</a></html>', $html);
    }


    /* HELPER METHODS */

    protected function getDomain()
    {
        $domain = Domain::whereEmail('noreply@uefa-league.com')->first();

        return $domain;
    }

    protected function modelTestAttrs()
    {
        $group = Group::whereName(TEST_GROUP_NAME)->first();

        return [
            'name' => self::CAMPAIGN_NAME,
            'schedule' => [
                'schedule_range' => '',
                'email_template_id' => EmailTemplate::whereName(self::EMAIL_TEMPLATE_NAME)->first()->id,
                'domain_id' => $this->getDomain()->id,
                'landing_id' => Landing::default()->first()->id,
            ],
            'groups' => [
                $group->id => $group->id,
            ],
        ];
    }

    protected function seedTestData()
    {
        \DB::table('recipients')->whereEmail(RECIPIENT_EMAIL)->delete();
        \DB::table('groups')->whereName(TEST_GROUP_NAME)->delete();

        $test_recipient = factory(Recipient::class)->state('test')->create();
        $test_group = factory(Group::class)->state('test')->create();

        $test_group->recipients()->attach($test_recipient->id);
    }
}