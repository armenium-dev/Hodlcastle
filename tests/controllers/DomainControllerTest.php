<?php namespace Tests\Controllers;

use App;
use App\User;
use Mockery;
use Tests\TestCase;
use App\Models\Company;
use App\Models\Domain;
use App\Http\Requests\CreateDomainRequest;
use App\Http\Requests\UpdateDomainRequest;
use App\Http\Controllers\DomainController;

class DomainControllerTest extends TestCase
{
    public $view_dir = 'domains';

    public $url = 'domains';

    public $var_single = 'domain';

    public function setUp()
    {
        parent::setUp();

        $this->setUserCustomer();
        $this->model = Domain::first();
    }

    public function testIndex()
    {
        $response = $this->responseIndex();

        $response->assertOk();
        $response->assertViewIs($this->view_dir . '.index');
    }

    public function testCreate()
    {
        $response = $this->responseCreate();

        $response->assertOk();
    }

    public function testStore()
    {
        $response = $this->responseStore();
        $class = App::make(DomainController::class);
        $request = new CreateDomainRequest;
        $request->merge([
            'name' => 'Test',
            'email' => 'test@test.it',
            'url' => 'test.url',
        ]);

        $return = $class->store($request);
        $response->assertStatus(302);
        $this->assertInstanceOf(\Illuminate\Http\RedirectResponse::class, $return);
    }

    public function testEdit()
    {
        $response = $this->responseEdit();

        $response->assertOk();
    }

    public function testUpdate()
    {
        $response = $this->responseUpdate();
        $class = App::make(DomainController::class);
        $request = new UpdateDomainRequest;

        $return = $class->update($this->model->id, $request);
        $response->assertStatus(302);
        $this->assertInstanceOf(\Illuminate\Http\RedirectResponse::class, $return);
    }

    public function testSend()
    {
        
    }
}