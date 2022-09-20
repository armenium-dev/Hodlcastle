<?php

namespace App\Mail\Transports;

use Illuminate\Mail\TransportManager;

class CustomTransportManager extends TransportManager
{
	public function createMailgunDriver() {
		$config = $this->app['config']->get('services.mailgun', []);
		
		return new ExtendedMailgunTransport(
			$this->guzzle($config),
			$config['secret'], $config['domain'], $config['zone']
		);
	}
}
