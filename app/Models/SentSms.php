<?php

namespace App\Models;

#use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Log;
use jdavidbakr\MailTracker\Model\SentEmail;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $hash
 * @property string $url
 * @property string $recipient
 * @property string $phone
 * @property int $campaign_id
 * @property int $opens
 * @property string $created_at
 * @property string $updated_at
 */
class SentSms extends Model{
	
	public $headers;
	protected $table = 'sent_sms';
	
	/**
	 * @var array
	 */
	protected $fillable = ['hash', 'url', 'recipient', 'phone', 'campaign_id', 'opens', 'created_at', 'updated_at'];
	
	/**
	 * Returns the header requested from our stored header info
	 */
	/*public function getHeader($key){
		
		$tracker       = SentSms::where('hash', $this->hash)->first();
		$this->headers = $tracker->headers;
		
		$headers = collect(preg_split("/\r\n|\n|\r/", $this->headers))->transform(function($header){
				list($key, $value) = explode(":", $header.":");
				
				return collect([
					'key'   => trim($key),
					'value' => trim($value)
				]);
			})->filter(function($header){
				return $header->get('key');
			})->keyBy('key')->transform(function($header){
				return $header->get('value');
			});
		
		return $headers->get($key);
	}*/
	
}
