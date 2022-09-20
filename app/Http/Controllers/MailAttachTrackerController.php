<?php

namespace App\Http\Controllers;

use Event;
use Illuminate\Routing\Controller;
use Response;
use App\Models\SentAttachEmails;
use App\Models\SentAttachEmailsUrlOpenes;
use jdavidbakr\MailTracker\Exceptions\BadUrlLink;
use App\Events\EmailAttachedEvent;
use jdavidbakr\MailTracker\Model\SentEmail;
use jdavidbakr\MailTracker\Events\EmailSentEvent;

class MailAttachTrackerController extends Controller{

	public function getF($url, $hash){
		$success_1 = $success_2 = $success_3 = false;

		$url = base64_decode(str_replace("$", "/", $url));
		if(filter_var($url, FILTER_VALIDATE_URL) === false){
			throw new BadUrlLink('Mail hash: '.$hash);
		}

		$success_1 = $this->updateSentEmails($hash);
		$success_2 = $this->updateSentAttachEmails($hash);
		$success_3 = $this->updateSentAttachEmailsUrlOpenes($url, $hash);

		/*$tracker = SentAttachEmails::where('hash', $hash)->first();

		if($tracker){
			$tracker->opens++;
			$tracker->save();
			$url_opened = SentAttachEmailsUrlOpenes::where('url', $url)->where('hash', $hash)->first();
			if($url_opened){
				$url_opened->opens++;
				$url_opened->save();
			}else{
				$url_opened = SentAttachEmailsUrlOpenes::create([
					'sent_attach_email_id' => $tracker->id,
					'url'           => $url,
					'hash'          => $tracker->hash,
					'opens' => 1
				]);
			}
			Event::dispatch(new EmailAttachedEvent($tracker));

			$success_1 = true;
		}*/



		if($success_1 || $success_2 || $success_3){
			return redirect($url);
		}

		throw new BadUrlLink('Mail hash: '.$hash);
	}

	private function updateSentEmails($hash){
		$ret = false;

		$tracker = SentEmail::where('hash', $hash)->first();

		if($tracker){
			$tracker->attach_opens++;
			$tracker->save();
			$ret = true;

			Event::dispatch(new EmailSentEvent($tracker));
		}

		return $ret;
	}

	private function updateSentAttachEmails($hash){
		$ret = false;

		$tracker = SentAttachEmails::where('hash', $hash)->first();

		if($tracker){
			$tracker->opens++;
			$tracker->save();
			$ret = true;

			Event::dispatch(new EmailAttachedEvent($tracker));
		}

		return $ret;
	}

	private function updateSentAttachEmailsUrlOpenes($url, $hash){
		$ret = false;

		$tracker = SentAttachEmails::where('hash', $hash)->first();
		$url_opened = SentAttachEmailsUrlOpenes::where('url', $url)->where('hash', $hash)->first();

		if($url_opened){
			$url_opened->opens++;
			$url_opened->save();
			$ret = true;
		}else{
			$url_opened = SentAttachEmailsUrlOpenes::create([
				'sent_attach_email_id' => $tracker->id,
				'url'           => $url,
				'hash'          => $tracker->hash,
				'opens' => 1
			]);

			$ret = true;
		}

		return $ret;
	}

}
