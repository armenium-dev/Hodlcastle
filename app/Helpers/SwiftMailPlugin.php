<?php

namespace App\Helpers;

use Swift_Events_SendEvent;
use App\Models\ShortLink;
use App\Models\Campaign;

class SwiftMailPlugin implements \Swift_Events_SendListener
{
    /**
     * Invoked immediately before the Message is sent.
     *
     * @param Swift_Events_SendEvent $evt
     */
    public function beforeSendPerformed(Swift_Events_SendEvent $evt)
    {
     
    	//$this->fixingTrackingURLs($message);
    	
        if(Campaign::getShort()){
            $message = $evt->getMessage();
            $this->replaceTrackingUrl($message);
        }
    }

    /**
     * Invoked immediately after the Message is sent.
     *
     * @param Swift_Events_SendEvent $evt
     */
    public function sendPerformed(Swift_Events_SendEvent $evt)
    {
        // TODO: Implement sendPerformed() method.
    }

    protected function replaceTrackingUrl($message)
    {
        $body = $message->getBody();

        preg_match_all("/<[Aa][\s]{1}[^>]*[Hh][Rr][Ee][Ff][^=]*=[ '\"\s]*([^ \"'>\s#]+)[^>]*>/", $body, $matches);
        $urls = $matches[1];

        for ($i = 0; $i < count($urls); $i++) {
            $url = parse_url($urls[$i]);

//            $code = ShortLink::generate($url['query']);
            $code = ShortLink::generate($urls[$i]);

            $replace = $url['scheme'] . '://' . env('SHORT_URL_DOMAIN') . '/short/' . $code->code;
            $body = str_replace($urls[$i], $replace, $body);
        }

        $message->setBody($body);
    }
	
	/** NOT USED
	 * @param $message
	 *
	 * @return mixed
	 */
	public function fixingTrackingURLs($message){
		$body = $message->getBody();
		
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
			}
		}
		$message->setBody($body);
		return $body;
	}
	
}
