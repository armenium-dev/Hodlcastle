<?php namespace Tests\Controllers;

use App\Models\Event;
use Tests\TestCase;
use Tests\Traits\RestForbiddenCustomer;
use Tests\Traits\RestAllowedCaptain;

class EventControllerTest extends TestCase
{
    use RestForbiddenCustomer;
    use RestAllowedCaptain;

    public $view_dir = 'events';

    public $url = 'events';

    public $var_single = 'event';

    public function setUp()
    {
        parent::setUp();

        $this->model = Event::first();
    }

}