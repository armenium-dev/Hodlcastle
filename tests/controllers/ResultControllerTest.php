<?php namespace Tests\Controllers;

use App\Models\Result;
use Tests\TestCase;
use Tests\Traits\RestForbiddenCustomer;
use Tests\Traits\RestAllowedCaptain;

class ResultControllerTest extends TestCase
{
    use RestForbiddenCustomer;
    use RestAllowedCaptain;

    public $view_dir = 'results';

    public $url = 'results';

    public $var_single = 'result';

    public function setUp()
    {
        parent::setUp();

        $this->model = Result::first();
    }

}