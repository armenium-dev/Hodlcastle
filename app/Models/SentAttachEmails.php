<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Log;
use jdavidbakr\MailTracker\Model\SentEmail;

/**
 * @property int $id
 * @property string $hash
 * @property string $url
 * @property string $recipient
 * @property int $opens
 * @property string $created_at
 * @property string $updated_at
 */
class SentAttachEmails extends Model
{

	public $headers;
	protected $table = 'sent_attach_emails';

    /**
     * @var array
     */
    protected $fillable = ['hash', 'url', 'recipient', 'opens', 'created_at', 'updated_at'];

	/**
	 * Returns the header requested from our stored header info
	 */
	public function getHeader($key)
	{

		$tracker = SentEmail::where('hash', $this->hash)->first();
		$this->headers = $tracker->headers;

		$headers = collect(preg_split("/\r\n|\n|\r/", $this->headers))
			->transform(function ($header) {
				list($key, $value) = explode(":", $header.":");
				return collect([
					'key' => trim($key),
					'value' => trim($value)
				]);
			})->filter(function ($header) {
				return $header->get('key');
			})->keyBy('key')
			->transform(function ($header) {
				return $header->get('value');
			});
		return $headers->get($key);
	}

}
