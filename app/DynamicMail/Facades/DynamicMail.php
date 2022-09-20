<?php

namespace App\DynamicMail\Facades;

use Illuminate\Support\Facades\Facade;
use Illuminate\Support\Testing\Fakes\MailFake;

class DynamicMail extends Facade
{
	
	/**
	 * Get the registered name of the component.
	 *
	 * @return string
	 */
	protected static function getFacadeAccessor(){
		return 'dynamic.mailer';
	}
}
