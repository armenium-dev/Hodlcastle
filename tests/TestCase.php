<?php

namespace Tests;

use App\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\Traits\CrudResponse;

abstract class TestCase extends BaseTestCase
{
    use CrudResponse;
    use CreatesApplication;
//    use DatabaseMigrations {
//        runDatabaseMigrations as baseRunDatabaseMigrations;
//    }

    protected $user;
    protected $model;

    public function setUp()
    {
        parent::setUp();

        $this->setUserCaptain();//var_dump('$user', $this->user->id);
    }

    protected function setUserCaptain()
    {
        $user = User::whereEmail('arybachu@verifysecurity.nl')->first();
        $this->assertInstanceOf(User::class, $user);

        $this->user = $user;
        $this->be($this->user);
    }

    protected function setUserCustomer()
    {
        $user = User::whereEmail('alchemistt@verifysecurity.nl')->first();
        $this->assertInstanceOf(User::class, $user);

        $this->user = $user;
        $this->be($this->user);
    }

//    public function __call($method, $args)
//    {
//        if (in_array($method, ['get', 'post', 'put', 'patch', 'delete']))
//        {
//            return $this->call($method, $args[0]);
//        }
////dd(method_exists($this, $method), $method);
//        //if(method_exists($this, $method))
//            call_user_func_array([$this, $method], $args);
//    }
}
