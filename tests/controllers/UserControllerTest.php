<?php namespace Tests\Controllers;

use App\User;
use Tests\TestCase;
use Tests\Traits\RestForbiddenCustomer;
use Tests\Traits\RestAllowedCaptain;

class UserControllerTest extends TestCase
{
    use RestForbiddenCustomer;
    use RestAllowedCaptain;

    public $view_dir = 'users';

    public $url = 'users';

    public $var_single = 'user';

    public function setUp()
    {
        parent::setUp();

        $this->model = User::first();
    }

}