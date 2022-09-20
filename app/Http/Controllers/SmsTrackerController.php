<?php

namespace App\Http\Controllers;

use Event;
use Exception;
use Illuminate\Routing\Controller;
use Response;
use App\Models\SentSms;
use App\Models\SentSmsUrlOpenes;
use App\Events\SmsEvent;

class SmsTrackerController extends Controller{
	
	public function getS($url, $hash){
		$success_1 = $success_2 = $success_3 = false;
		
		$url = base64_decode(str_replace("$", "/", $url));
		if(filter_var($url, FILTER_VALIDATE_URL) === false){
			throw new Exception('SMS hash: '.$hash);
		}
		
		$success_1 = $this->updateSentSms($hash);
		$success_2 = $this->updateSentSmsUrlOpenes($url, $hash);
		
		/*$tracker = SentSms::where('hash', $hash)->first();

		if($tracker){
			$tracker->opens++;
			$tracker->save();
			$url_opened = SentSmsUrlOpenes::where('url', $url)->where('hash', $hash)->first();
			if($url_opened){
				$url_opened->opens++;
				$url_opened->save();
			}else{
				$url_opened = SentSmsUrlOpenes::create([
					'sent_attach_email_id' => $tracker->id,
					'url'           => $url,
					'hash'          => $tracker->hash,
					'opens' => 1
				]);
			}
			Event::dispatch(new SmsEvent($tracker));

			$success_1 = true;
		}*/
		
		
		if($success_1 || $success_2){
			return redirect($url);
		}
		
		throw new Exception('SMS hash: '.$hash);
	}
	
	private function updateSentSms($hash){
		$ret = false;
		
		$tracker = SentSms::where('hash', $hash)->first();
		
		if($tracker){
			$tracker->opens++;
			$tracker->save();
			$ret = true;
			
			Event::dispatch(new SmsEvent($tracker));
		}
		
		return $ret;
	}
	
	private function updateSentSmsUrlOpenes($url, $hash){
		$ret = false;
		
		$tracker    = SentSms::where('hash', $hash)->first();
		$url_opened = SentSmsUrlOpenes::where('url', $url)->where('hash', $hash)->first();
		
		if($url_opened){
			$url_opened->opens++;
			$url_opened->save();
			$ret = true;
		}else{
			$url_opened = SentSmsUrlOpenes::create([
				'sent_sms_id' => $tracker->id,
				'url'         => $url,
				'hash'        => $tracker->hash,
				'opens'       => 1
			]);
			
			$ret = true;
		}
		
		return $ret;
	}
	
}
