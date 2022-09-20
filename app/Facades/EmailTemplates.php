<?php namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class EmailTemplates extends Facade
{
    protected static function getFacadeAccessor() { return 'EmailTemplates'; }
}