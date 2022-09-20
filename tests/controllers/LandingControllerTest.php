<?php namespace Tests\Controllers;

use App\Models\Landing;
use Tests\TestCase;
use Tests\Traits\RestForbiddenCustomer;
use Tests\Traits\RestAllowedCaptain;

class LandingControllerTest extends TestCase
{
    use RestForbiddenCustomer;
    use RestAllowedCaptain;

    public $view_dir = 'landings';

    public $url = 'landings';

    public $var_single = 'landing';

    public function setUp()
    {
        parent::setUp();

        $this->model = Landing::first();
    }

}