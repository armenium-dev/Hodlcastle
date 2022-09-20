<?php namespace Tests\Controllers;

use App\Permission;
use Tests\TestCase;
use Tests\Traits\RestForbiddenCustomer;
use Tests\Traits\RestAllowedCaptain;

class PermissionControllerTest extends TestCase
{
    use RestForbiddenCustomer;
    use RestAllowedCaptain;

    public $view_dir = 'permissions';

    public $url = 'permissions';

    public $var_single = 'permission';

    public function setUp()
    {
        parent::setUp();

        $this->model = Permission::first();
    }


}