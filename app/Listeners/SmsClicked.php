<?php namespace App\Listeners;

use App\Models\Campaign;
use App\Models\Result;
use App\Events\SmsEvent;

class SmsClicked extends SmsBaseListener{
	
	/**
	 * Handle the event.
	 *
	 * @param SmsEvent $event
	 *
	 * @return void
	 */
	public function handle(SmsEvent $event){
		$this->event = $event;
		
		if(!$this->process()){
			return;
		}
		
		$eventModel = $this->createEvent(Result::TYPE_SMISH);
		
		$result_check = $this->resultCheck(Result::TYPE_SMISH);
		$geo_info     = geoInfo();
		
		if(!$result_check->count()){
			$this->createResult($eventModel, Result::TYPE_SMISH, $geo_info);
		}
	}
	
}