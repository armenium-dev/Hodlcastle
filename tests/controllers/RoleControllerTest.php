<?php namespace Tests\Controllers;

use App\Role;
use Tests\TestCase;
use Tests\Traits\RestForbiddenCustomer;
use Tests\Traits\RestAllowedCaptain;

class RoleControllerTest extends TestCase
{
    use RestForbiddenCustomer;
    use RestAllowedCaptain;

    public $view_dir = 'roles';

    public $url = 'roles';

    public $var_single = 'role';

    public function setUp()
    {
        parent::setUp();

        $this->model = Role::first();
    }

}