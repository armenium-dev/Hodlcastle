<?php namespace Tests\Controllers;

use App;
use Tests\TestCase;
use Mockery as m;
use App\Models\Group;
use App\Models\Company;
use App\Http\Controllers\GroupController;
use App\Http\Requests\CreateGroupRequest;
use App\Http\Requests\UpdateGroupRequest;

class GroupControllerTest extends TestCase
{
    public $view_dir = 'groups';

    public $url = 'groups';

    public $var_single = 'group';

    public function setUp()
    {
        parent::setUp();

        $this->model = Group::first();
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
        $class = App::make(GroupController::class);
        $request = new CreateGroupRequest;
        $request->merge([
            'name' => 'Test',
        ]);

        //dd('$this->user->id', $this->user->id);
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
        $class = App::make(GroupController::class);
        $request = new UpdateGroupRequest;

        $return = $class->update($this->model->id, $request);
        $response->assertStatus(302);
        $this->assertInstanceOf(\Illuminate\Http\RedirectResponse::class, $return);
    }

    public function test_import()
    {
        
    }

    public function test_vue()
    {
        
    }
}