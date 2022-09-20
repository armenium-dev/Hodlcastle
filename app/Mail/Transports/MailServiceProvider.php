<?php

namespace App\Mail\Transports;
use Auth;

class MailServiceProvider extends \Illuminate\Mail\MailServiceProvider
{
	public function registerSwiftTransport()
	{
		#dd($this->app['config']['mail']);
		if($this->app['config']['mail.driver'] == 'mailgun'){
			$this->app->singleton('swift.transport', function($app){
				return new CustomTransportManager($app);
			});
		}else{
			parent::registerSwiftTransport();
		}
	}
}
