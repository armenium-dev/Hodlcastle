<?php

namespace App\Helpers;

use App\Models\Campaign;
use App\Models\ShortLink;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use PHPExcel_IOFactory;
use Swift_Attachment;
use Swift_Events_SendEvent;
use Swift_Events_SendListener;
use App\Models\SentAttachEmails;

class SwiftMailAttachPlugin implements Swift_Events_SendListener {
	/**
	 * Invoked immediately before the Message is sent.
	 *
	 * @param Swift_Events_SendEvent $evt
	 */
	public function beforeSendPerformed(Swift_Events_SendEvent $evt){
		$path = public_path('attaches');

		if(!is_dir($path)){
			@mkdir($path, 0777);
		}

		$message = $evt->getMessage();

		if(Campaign::getWithAttachment()){
			$this->setAttachment($message);
		}

		$this->replaceImgUrls($message);
	}

	/**
	 * Invoked immediately after the Message is sent.
	 *
	 * @param Swift_Events_SendEvent $evt
	 */
	public function sendPerformed(Swift_Events_SendEvent $evt){
		// TODO: Implement sendPerformed() method.
	}

	protected function setAttachment($message){
		$attach_file = $this->createAttacheFile($message);
		if($attach_file){
			$message->attach(Swift_Attachment::fromPath($attach_file));
		}
	}

	public function createAttacheFile($message){

		$url = $this->makeTrackingUrl($message);

		$inputFileType  = 'Excel2007';
		$inputFileName  = 'Document-template01.xlsm';
		$inputFile      = public_path('file_templates/'.$inputFileName);
		$outputFileName = public_path('attaches/'.$this->generateAttachFileName($inputFileName));

		$objReader   = PHPExcel_IOFactory::createReader($inputFileType);
		$objPHPExcel = $objReader->load($inputFile);
		$objPHPExcel->setActiveSheetIndex(0);
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(25, 101, $url);
		$objPHPExcel->getActiveSheet()->setTitle('Simple');
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, $inputFileType);
		$objWriter->save($outputFileName);

		return file_exists($outputFileName) ? $outputFileName : false;
	}

	public function generateAttachFileName($inputFileName){
		$file_ext = $this->get_file_ext($inputFileName);
		$a = explode('-', str_replace('.'.$file_ext, '', $inputFileName));
		$a[1] = Campaign::getRecipiendCode() or time();
		$new_file_name = implode('-', $a).'.'.$file_ext;

		return $new_file_name;
	}

	public function get_file_ext($file_path){
		$base_name = basename($file_path);
		$a         = explode('.', $base_name);
		$ext       = end($a);

		return strtolower($ext);
	}

	public function makeTrackingUrl($message){

		#dd($domain); exit;

		$tracking_url = env('PHISHING_MANAGER_LANDING_PAGE_URL', config('app.url').'/landingpage/');

		if(strpos($tracking_url, 'http://') !== 0 && strpos($tracking_url, 'https://') !== 0){
			$tracking_url = 'http://'.$tracking_url;
		}

		$body = $message->getBody();
		//$body = $this->fixingTrackingURLs($body);

		preg_match_all("/<[Aa][\s]{1}[^>]*[Hh][Rr][Ee][Ff][^=]*=[ '\"\s]*([^ \"'>\s#]+)[^>]*>/", $body, $matches);

		if(isset($matches[1]) && !empty($matches[1])){
			$tracking_url = end($matches[1]);
			$tracking_url = str_replace('email/l', 'filebased/l', $tracking_url);
		}

		if(Campaign::getShort()){
			$tracking_url = $this->generateShortUrl($tracking_url, true);
		}

		$a = explode('/', $tracking_url);
		$hash = end($a);

		$get_To = $message->getTo();
		$to_email = key($get_To);
		$to_name = !empty($get_To[$to_email]) ? $get_To[$to_email] : $to_email;
		$recipient = $to_name.' <'.$to_email.'>';
		#dd($recipient); exit;

		/*
		$matches = [];
		preg_match_all("/<[Ii][Mm][Gg][\s]{1}[^>]*[Ss][Rr][Cc][^=]*=[ '\"\s]*([^ \"'>\s#]+)[^>]*>/", $body, $matches);
		$urls = $matches[1];

		for($i = 0; $i < count($urls); $i++){
			if(strstr($urls[$i], 'storage') !== false){
				$replace = 'https://'.$domain.'/storage';
				$body = str_replace('/storage', $replace, $body);
			}
		}
		$new_body_hash = md5($body);

		if($body_hash != $new_body_hash){
			$message->setBody($body);
		}
		*/

		SentAttachEmails::create([
			'hash' => $hash,
			'url' => $tracking_url,
			'recipient' => $recipient,
		]);

		return $tracking_url;
	}
	
	/** NOT USED
	 * @param $body
	 *
	 * @return mixed
	 */
	public function fixingTrackingURLs($body){
		
		preg_match_all("/<[Aa][\s]{1}[^>]*[Hh][Rr][Ee][Ff][^=]*=[ '\"\s]*([^ \"'>\s#]+)[^>]*>/", $body, $matches);
		
		if(isset($matches[1]) && !empty($matches[1])){
			$tracking_url = end($matches[1]);
			$a = explode('/', $tracking_url);
			$a = array_reverse($a);
			$encoded_url = $a[1];
			$url = base64_decode($encoded_url);
			$b = explode('http', $url);
			if(count($b) > 1){
				$new_url = 'http'.$b[1];
				$new_url = str_replace("/", "$", base64_encode($new_url));
				$body = str_replace($encoded_url, $new_url, $body);
			//dd($encoded_url);
			}
		}
		
		//exit;
		return $body;
	}
	
	public function generateShortUrl($src_url, $logging = false){

		if($logging)
			Log::stack(['custom'])->debug($src_url);

		$url = parse_url($src_url);
		$code = ShortLink::generate($src_url);
		$dst_url = $url['scheme'] . '://' . env('SHORT_URL_DOMAIN') . '/short/' . $code->code;

		if($logging)
			Log::stack(['custom'])->debug($dst_url);

		return $dst_url;
	}

	public function replaceImgUrls($message){
		$domain = Campaign::getDomain();

		$body = $message->getBody();
		$body_hash = md5($body);

		$matches = [];
		preg_match_all("/<[Ii][Mm][Gg][\s]{1}[^>]*[Ss][Rr][Cc][^=]*=[ '\"\s]*([^ \"'>\s#]+)[^>]*>/", $body, $matches);
		$urls = $matches[1];

		for($i = 0; $i < count($urls); $i++){
			if(strstr($urls[$i], 'storage') !== false){
				$replace = 'https://'.$domain.'/storage';
				$body = str_replace('/storage', $replace, $body);
			}
		}
		$new_body_hash = md5($body);

		if($body_hash != $new_body_hash){
			$message->setBody($body);
		}

	}

}