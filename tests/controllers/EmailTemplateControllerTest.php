<?php namespace Tests\Controllers;

use App;
use Tests\TestCase;
use Mockery as m;
use App\Models\EmailTemplate;
use App\Models\Company;
use App\Http\Controllers\EmailTemplateController;
use App\Http\Requests\UpdateEmailTemplateRequest;

class EmailTemplateControllerTest extends TestCase
{
    protected $company;

    protected $repositoryContract;

    public $view_dir = 'email_templates';

    public $url = 'emailTemplates';

    public $var_single = 'emailTemplate';

    public function setUp()
    {
        parent::setUp();

        $this->mock = m::mock(EmailTemplate::class);
        $this->repositoryContract = m::mock('App\Repositories\EmailTemplateRepository');
    }

    public function testIndex()
    {
        $response = $this->call('GET', $this->url);

        $vars = ['emailTemplates', 'emailTemplatesPublic'];
        $response->assertViewHas($vars);
        $response->assertViewIs('email_templates.index');

        foreach ($vars as $var_name) {
            $var = $response->original->getData()[$var_name];
            $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $var);
        }
    }

    public function testUpdate()
    {
        $model = EmailTemplate::first();
        $class = App::make(EmailTemplateController::class);
        $request = new UpdateEmailTemplateRequest;

        $return = $class->update($model->id, $request);

        $this->assertInstanceOf(\Illuminate\Http\RedirectResponse::class, $return);
    }

    public function testShowCaptain()
    {
        $this->setModelDoesntBelongToCurrentUser(false);

        $response = $this->responseShow();

        $response->assertOk();
        $response->assertViewHas([$this->var_single]);
        $response->assertViewIs($this->view_dir . '.show');
    }

    public function testShowCustomerNotHisModelPublicSuccess()
    {
        $this->setUserCustomer();

        $this->setModelDoesntBelongToCurrentUser(true);

        $response = $this->responseShow();

        $response->assertOk();
        $response->assertViewHas([$this->var_single]);
        $response->assertViewIs($this->view_dir . '.show');
    }

    public function testShowCustomerNotHisModelNotPublicFails()
    {
        $this->setUserCustomer();

        $this->setModelDoesntBelongToCurrentUser(false);

        $response = $this->responseShow();

        $response->assertForbidden();
    }

    public function testEditCaptain()
    {
        $this->setModelDoesntBelongToCurrentUser(false);

        $response = $this->responseEdit();

        $response->assertViewHas([$this->var_single]);
        $response->assertViewIs($this->view_dir . '.edit');
    }

    public function testEditCustomerNotHisModelPublicFails()
    {
        $this->setUserCustomer();

        $this->setModelDoesntBelongToCurrentUser(true);

        $response = $this->responseEdit();

        $response->assertNotFound();
    }

    public function testEditCustomerNotHisModelNotPublicFails()
    {
        $this->setUserCustomer();

        $this->setModelDoesntBelongToCurrentUser(false);

        $response = $this->responseEdit();

        $response->assertNotFound();
    }

    public function test_copy_captain_not_his_model_public_success()
    {
        $this->setModelDoesntBelongToCurrentUser(true);

        $response = $this->responseCopy();

        $response->assertViewHas([$this->var_single]);
        $response->assertViewIs($this->view_dir . '.copy');
    }

//    public function test_copy_captain_not_his_model_not_public_fail()
//    {
//        $this->setModelDoesntBelongToCurrentUser(false);
//
//        $response = $this->responseCopy();
//
//        $response->assertNotFound();
//    }

    public function testCopyCustomerNotHisModelPublicSuccess()
    {
        $this->setUserCustomer();

        $this->setModelDoesntBelongToCurrentUser(true);

        $response = $this->responseCopy();

        $response->assertOk();
        $response->assertViewHas([$this->var_single]);
        $response->assertViewIs($this->view_dir . '.copy');
    }

    public function testCopyCustomerNotHisModelNotPublicFails()
    {
        $this->setUserCustomer();

        $this->setModelDoesntBelongToCurrentUser(false);

        $response = $this->responseCopy();

        $response->assertNotFound();
    }

    public function testTestSend()
    {
        //$this->setUserCustomer();

        $this->setCompanyDoesntBelongToCurrentUser();

        $params = [
            'subject' => 'Test',
            'editor-html' => 'Test html',
            'company_id' => $this->company->id,
            //'company_id' => $this->user->company_id,
        ];

        $request = $this->call('POST', $this->url . '/test', $params);

        $request->assertJson([
            'result' => 1,
        ]);
    }

    protected function setModelDoesntBelongToCurrentUser($is_public = false)
    {
        $model = EmailTemplate::where('company_id', '!=', $this->user->company_id)
            ->where('is_public', $is_public)
            ->first()
            ;

        if (!$model) {
            $model = factory(EmailTemplate::class)->create([
                'company_id' => Company::where('id', '!=', $this->user->company_id)->first()->id,
                'is_public' => $is_public,
            ]);
        }

        $this->model = $model;
    }

    protected function setCompanyDoesntBelongToCurrentUser()
    {
        $this->company = Company::where('id', '!=', $this->user->company_id)->first();
    }
}
