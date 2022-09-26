<?php namespace App\Models;

use App;
use App\Events\SmsSentEvent;
use App\User;
use Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Mail;
use URL;
use Config;
use App\DynamicMail\Facades\DynamicMail;
use Dotunj\LaraTwilio\Facades\LaraTwilio;
use App\Models\SentSms;
use Flash;
use Event;

/**
 * Class SmsTemplate
 * @package App\Models
 */

class SmsTemplate extends Model{
	use SoftDeletes;

	public $table = 'sms_templates';

	protected $dates = ['deleted_at'];

	/**
	 * The attributes that could be used in mass assignment.
	 * @var array
	 */
	protected $fillable = [
		'company_id',
		'language_id',
		'name',
		'content',
		'is_public',
	];

	/**
	 * The attributes that should be casted to native types.
	 * @var array
	 */
	protected $casts = [
		'company_id'  => 'integer',
		'language_id' => 'integer',
		'name'        => 'string',
		'content'     => 'string',
		'is_public'   => 'integer',
		//'link_name' => 'string',
	];

	/**
	 * Validation rules
	 * @var array
	 */
	public static $rules = [
		'name'    => 'required',
		'content' => 'required',
	];

	public function image(){
		return $this->morphOne('App\Models\Image', 'imageable')->latest();
	}

	public function company(){
		return $this->belongsTo('App\Models\Company');
	}

	public function campaigns(){
		return $this->hasMany('App\Models\Campaign');
	}

	public function language(){
		return $this->belongsTo('App\Models\Language');
	}

	public static function boot(){
		self::creating(function($model){
			if(!$model->language_id){
				$model->language_id = 1;
			}
		});

		self::updating(function($model){
			if(!$model->language_id){
				$model->language_id = 1;
			}
		});
	}

    /**
     * Send email based on current template. Including data required for tracking
     *
     * @param Recipient $recipient
     * @param null      $campaign
     * @param bool      $test
     *
     * @return bool
     */
	public function send(Recipient $recipient, $campaign = null){
		Log::stack(['custom'])->debug(__CLASS__.'::'.__METHOD__);
        #dd($recipient);
        #dd($this->content);

		$sendSms = false;

		$content_data = $this->buildContent($recipient, $campaign);
		
		$content = $content_data['content'];
		$tracker = $content_data['tracker'];
		
		if(!empty($recipient->mobile)){
			try{
				$sendSms = LaraTwilio::notify($recipient->mobile, $content);
				Log::stack(['custom'])->debug($recipient->mobile.' sent');
			}catch(\Exception $exception){
				Log::stack(['custom'])->debug($exception->getMessage());
				Flash::error($exception->getMessage());
			}
		}
		
		#dd($tracker);
		if($sendSms){
			Event::dispatch(new SmsSentEvent($tracker));
        }

		return $sendSms;
	}

	public function buildContent(Recipient $recipient, $campaign = null){
		
		$data = $this->makeTrackingUrl($recipient, $campaign);
		
		$with = [
			'.FirstName'  => $recipient->first_name,
			'.LastName'   => $recipient->last_name,
			#'.Sms'      => $recipient->mobile,
			#'.From'       => env('TWILIO_SMS_FROM'),
			#'.Position'   => $recipient->position,
			#'.Department' => $recipient->department,
			'.YEAR'       => date('Y'),
			'.MONTH'      => date('m'),
			'.DAY'        => date('d'),
			'.URL'        => $data['url'],
		];

		$login_variables = $this->getLoginVariables();

		foreach($login_variables as $login_variable){
			$with['.'.$login_variable['variable']] = $this->makeFakeLoginPageUrl($recipient, $campaign, $login_variable['path']);
		}

		$content = $this->content;
		foreach($with as $k => $v){
			$content = str_replace('{{'.$k.'}}', $v, $content);
		}

		#dd($with);


		return [
			'tracker' => $data['tracker'],
			'content' => $content,
		];
	}

	public function makeTrackingUrl(Recipient $recipient, $campaign = null){
		//$tracking_url = '';
		$campaignWithPivot = null;

		#Log::stack(['custom'])->debug('campaign = '.$campaign->id);
		#Log::stack(['custom'])->debug('recipient = '.$recipient->campaigns());
		if($campaign){
			$campaignWithPivot = $recipient->campaigns()->find($campaign->id);
		}
		#Log::stack(['custom'])->debug('campaignWithPivot = '.$campaignWithPivot);
		
		if(!$campaignWithPivot){
			throw new \Exception('Recipient got no Campaign');
		}else{
			if(!$campaignWithPivot->schedule){
				throw new \Exception('Campaign got no schedule');
			}
		}
		
		$tracking_url = env('PHISHING_MANAGER_LANDING_PAGE_SMS_URL', config('app.url').'/landingpage3');

		if(strpos($tracking_url, 'http://') !== 0 && strpos($tracking_url, 'https://') !== 0){
			$tracking_url = 'http://'.$tracking_url;
		}

		$send_to_landing = $campaignWithPivot->schedule->send_to_landing;
		$redirect_url    = $campaignWithPivot->schedule->redirect_url;

		if(intval($send_to_landing) == 0 && !empty($redirect_url)){
			$tracking_url = $redirect_url;
		}

		#dd($tracking_url);

		do{
			$hash = Str::random(32);
			$used = SentSms::where('hash', $hash)->count();
		}while($used > 0);

		$tracking_url = str_replace('&amp;', '&', $tracking_url);
		$url = str_replace("/", "$", base64_encode($tracking_url));
		$url .= '/'.$hash;
		$tracking_url = str_replace('landingpage3', 'sms/l/', $tracking_url).$url;
		
		$tracker = SentSms::create([
			'hash' => $hash,
			'url' => $tracking_url,
			'recipient' => $recipient->first_name,
			'phone' => $recipient->mobile,
			'campaign_id' => $campaign->id,
		]);

		#dd($tracking_url);

		return [
			'tracker' => $tracker,
			'url' => $this->generateShortUrl($tracking_url, true)
		];
	}

	protected function createTrackers($message){

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

	public function makeFakeLoginPageUrl(Recipient $recipient, $campaign = null, $login_page = ''){
		//$tracking_url = '';
		$campaignWithPivot = null;

		if($campaign){
			$campaignWithPivot = $recipient->campaigns()->find($campaign->id);
		}

		/*if($campaignWithPivot){
			$href = $campaignWithPivot->schedule->domain->url.'?rid='.$campaignWithPivot->pivot->code;

			if(!$campaignWithPivot->schedule){
				throw new \Exception('Campaign got no schedule');
			}
			if(!$campaignWithPivot->schedule->domain){
				throw new \Exception('Campaign got no domain');
			}
		}*/

		$params = $recipient->email.','.$campaign->id;
		$params = base64_encode($params);

		$tracking_url = env('PHISHING_MANAGER_LOGIN_PAGE_URL', config('app.url').'/account/'.$login_page.'?id='.$params);

		if(strpos($tracking_url, 'http://') !== 0 && strpos($tracking_url, 'https://') !== 0){
			$tracking_url = 'http://'.$tracking_url;
		}

		$send_to_landing = $campaignWithPivot->schedule->send_to_landing;
		$redirect_url    = $campaignWithPivot->schedule->redirect_url;

		if(intval($send_to_landing) == 0 && !empty($redirect_url)){
			$tracking_url = $redirect_url;
		}

		return $tracking_url;
	}

	public function getLoginVariables(){
		$root        = dirname(__DIR__, 2);
		$dir         = $root.'/public/account/';
		$results     = [];
		$login_pages = $this->getSubFolders($dir);
		foreach($login_pages as $login_page){
			$login_page_folders = $this->getSubFolders($dir.$login_page);
			$login_page_files   = $this->getFolderFiles($dir.$login_page);
			if(in_array('assets', $login_page_folders) && in_array('index.html', $login_page_files)){
				$results[] = [
					'variable'    => 'login-'.$login_page,
					'path'        => $login_page,
					'description' => 'URL to fake '.strtoupper($login_page).' login page '
				];
			}
		}

		return $results;
	}

	public function getFolderFiles($dir){
		$items = scandir($dir);
		$files = [];
		foreach($items as $key => $value){
			$path = realpath($dir.DIRECTORY_SEPARATOR.$value);
			if(!is_dir($path)){
				$files[] = $value;
			}
		}

		return $files;
	}

	public function getSubFolders($dir){
		$files   = scandir($dir);
		$folders = [];
		foreach($files as $key => $value){
			$path = realpath($dir.DIRECTORY_SEPARATOR.$value);
			if($value != "." && $value != ".." && is_dir($path)){
				$folders[] = $value;
			}
		}

		return $folders;
	}

}
