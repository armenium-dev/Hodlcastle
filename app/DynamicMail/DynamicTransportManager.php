<?php

namespace App\DynamicMail;

use App\Mail\Transports\ExtendedMailgunTransport;
use Illuminate\Mail\TransportManager as IlluminateTransportManager;

class DynamicTransportManager extends IlluminateTransportManager {
	
	public function createMailgunDriver() {
		$config = $this->app['config']->get('services.mailgun', []);
		
		return new ExtendedMailgunTransport(
			$this->guzzle($config),
			$config['secret'], $config['domain'], $config['zone']
		);
	}
	
	public function getDriverCallback($driver){
		return array_key_exists($driver, $this->drivers) ? $this->drivers[$driver] : null;
	}
	
	public function setDriverCallback($driver, $driverCallback){
		if(is_null($driverCallback) && array_key_exists($driver, $this->drivers)){
			unset($this->drivers[$driver]);
		}else{
			$this->drivers[$driver] = $driverCallback;
		}
	}
	
	public function resetDriverCallback($driver){
		$this->setDriverCallback($driver, null);
	}
}
