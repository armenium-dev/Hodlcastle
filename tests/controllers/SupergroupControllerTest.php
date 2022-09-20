<?php namespace Tests\Controllers;

use App\Models\Supergroup;
use Tests\TestCase;
use Tests\Traits\RestForbiddenCustomer;
use Tests\Traits\RestAllowedCaptain;

class SupergroupControllerTest extends TestCase
{
    use RestForbiddenCustomer;
    use RestAllowedCaptain;

    public $view_dir = 'supergroups';

    public $url = 'supergroups';

    public $var_single = 'supergroup';

    public function setUp()
    {
        parent::setUp();

        $this->model = Supergroup::first();
        if (!$this->model)
            $this->model = factory(Supergroup::class)->create();
    }

    public function test_vue_schedules()
    {
        
    }

    public function test_generate()
    {
        
    }
}