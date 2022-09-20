<?php

namespace App\DynamicMail;

use App;
use Auth;
use Illuminate\Contracts\Mail\Mailable as MailableContract;
use Illuminate\Contracts\Queue\Factory as QueueContract;
use Illuminate\Mail\Mailer as IlluminateMailer;

class DynMailer extends IlluminateMailer
{
	
	/** @var array An array containing the driver callback and name */
	protected $customDriver = [];
	
	public function byUserRole($mail_driver = 'default'){
		$config = $this->getConfig($mail_driver);
		#dd($config);
		return $this->via($config['driver'], $config);
	}
	
	/**
	 * Create a new mailer instance based on driver (and config for given driver)
	 *
	 * @param string $driver
	 * @param array  $config
	 *
	 * @return \App\DynamicMail\DynMailer
	 */
	public function via($driver, array $config = [])
	{
		$newInstance = clone $this;
		
		$newInstance->prepareDriver();
		
		$newInstance->customDriver['name'] = $driver;
		$transporter = $newInstance->customDriver['callback']($newInstance->customDriver['name'], $config);
		
		$newInstance->setSwiftMailer(new \Swift_Mailer($transporter));
		
		return $newInstance;
	}
	
	/**
	 * Sets a new Swift mailer instance and set config for current mailer driver
	 *
	 * @param array $config
	 *
	 * @return \App\DynamicMail\DynMailer
	 */
	public function with(array $config)
	{
		if ($this->prepareDriver()) {
			$instance = clone $this;
		} else {
			$instance = $this;
		}
		
		/** @var \Swift_Transport $transporter */
		$transporter = $instance->customDriver['callback']($instance->customDriver['name'], $config);
		
		$instance->setSwiftMailer(new \Swift_Mailer($transporter));
		
		return $instance;
	}
	
	/**
	 * Sets the customDriver property if not set via the "via" method.
	 * This makes it possible to override config for default driver without the need to call "via" first.
	 *
	 * @return bool true if had to be prepared.
	 */
	protected function prepareDriver()
	{
		if (empty($this->customDriver)) {
			
			/** @var \Illuminate\Support\Manager $manager */
			$manager = app('dynamic.transport');
			
			/** @var callable $customDriver */
			$customDriver = $manager->driver('dynamic.driver');
			
			$config = app('config')->get('mail');
			
			$this->customDriver = ['callback' => $customDriver, 'name' => $config['driver']];
			
			return true;
		}
		return false;
	}
	
	public function getConfig($mail_driver = 'default'){
		$config = config('mail');
		
		//if(Auth::check() && Auth::user()->hasRole('captain')){
		if($mail_driver == 'mailgun'){
			$new_config = [
				'driver'     => env('MAILGUN_DRIVER'),
				'host'       => env('MAILGUN_HOST'),
				'port'       => env('MAILGUN_PORT'),
				'username'   => env('MAILGUN_USERNAME'),
				'password'   => env('MAILGUN_PASSWORD'),
				'encryption' => env('MAILGUN_ENCRYPTION'),
				'zone'       => env('MAILGUN_ZONE'),
			];
			
			$config = array_merge($config, $new_config);
		}
		
		return $config;
	}
	
	/**
	 * Send a new message using a view.
	 *
	 * @param  string|array|MailableContract  $view
	 * @param  array  $data
	 * @param  \Closure|string  $callback
	 * @return void
	 */
	/*public function send($view, array $data = [], $callback = null){
		parent::send($view, $data, $callback);
	}
	
	public function setQueue(QueueContract $queue){
		parent::setQueue($queue);
	}
	
	public function alwaysFrom($address, $name = null)
	{
		parent::alwaysFrom($address, $name);
	}
	
	public function setSwiftMailer($swift)
	{
		parent::setSwiftMailer($swift);
	}*/
	
}
