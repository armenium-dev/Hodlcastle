<?php namespace App\Models;

use App;
use App\User;
use Auth;
use Illuminate\Database\Eloquent\SoftDeletes;
use JDT\LaravelEmailTemplates\TemplateMailable;
use Mail;
use URL;
use Config;
use App\DynamicMail\Facades\DynamicMail;
use Illuminate\Support\Facades\Log;

/**
 * Class EmailTemplate
 * @package App\Models
 * @version June 27, 2018, 11:46 am UTC
 * @property string lang
 * @property string tags
 */
class EmailTemplate extends \JDT\LaravelEmailTemplates\Entities\EmailTemplate {

	use SoftDeletes;

	const TYPE_PLAIN = 0;
	const TYPE_HTML = 1;

	public $table = 'email_templates';

	protected $dates = ['deleted_at'];

	/**
	 * The attributes that could be used in mass assignment.
	 * @var array
	 */
	protected $fillable = [
		//'handle',
		'name',
		'subject',
		'html',
		'text',
		'with_attach',
		'language_id',
		'tags',
		'is_public',
		'text_type',
		'company_id',
		'type_text',
	];

	/**
	 * The attributes that should be casted to native types.
	 * @var array
	 */
	protected $casts = [
		//'handle' => 'string',
		'name'        => 'string',
		'subject'     => 'string',
		'html'        => 'string',
		'text'        => 'string',
		'language_id' => 'integer',
		'tags'        => 'string',
		//'link_name' => 'string',
	];

	/**
	 * Validation rules
	 * @var array
	 */
	public static $rules = [
		'name'    => 'required',
		//'handle' => 'required',
		'subject' => 'required',
		//'link_name' => 'required',
		//        'html' => 'string',
		//        'text' => 'string',
		//'lang' => 'required|alpha',
		//        'tags' => 'string',
		//'email_template_id' => 'required',
	];

	public function image(){
		return $this->morphOne('App\Models\Image', 'imageable');
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
			if(!$model->tags){
				$model->tags = '';
			}
		});

		self::updating(function($model){
			if(!$model->tags){
				$model->tags = '';
			}
		});
	}

	/**
	 * Adapter method to connect jdtsoftware package to the project
	 * @return mixed
	 */
	public function getContentAttribute(){
		return $this->html;
	}

	public function getIsHtmlAttribute(){
		return is_null($this->type_text) || $this->type_text == self::TYPE_HTML;
	}

	public function getIsPlainAttribute(){
		return $this->type_text == self::TYPE_PLAIN;
	}

	/**
	 * Send email based on current template. Including data required for tracking
	 *
	 * @param Recipient $recipient
	 * @param null $campaign
	 * @param bool $test
	 */
	public function send(Recipient $recipient, $campaign = null, $test = false, $training = null){
		if(!$campaign) return;

		if($campaign->schedule->domain){
			URL::forceRootUrl('https://'.$campaign->schedule->domain->domain);
		}

		$mailTemplate = $this->buildMailTemplate($recipient, $campaign, $test, $training);
		$build        = $mailTemplate->build();

		$content = $this->is_html ? $build['html'] : $build['text'];

		Mail::send('emails.send', ['title' => 'title', 'content' => $content], function($message) use ($recipient, $campaign, $test){
			if($test){
				$from = config('mail.test_email');
			}elseif($campaign){
				$from = $campaign->email;
			}else{
				$from = config('mail.email_noreply');
			}

			$message->from($from, $from);
			$message->to($recipient->email);
			$message->subject($this->subject);

			if($campaign){
				$company = Auth::check() && Auth::user()->hasRole('customer') ? Auth::user()->company : $this->company;
				$message->getHeaders()->addTextHeader('X-Campaign-ID', $campaign ? $campaign->id : 0);
				$message->getHeaders()->addTextHeader('X-PMID', $company->created_at->timestamp);
			}
		});

		#Log::stack(['custom'])->debug($build);

		if($campaign){
			URL::forceRootUrl(env('APP_URL'));
		}
	}

	public function buildMailTemplate(Recipient $recipient, $campaign = null, $test = false, $training = null){
		$this->text = $this->text ? $this->text : '';
		$this->html = $this->html ? $this->html : '';

		$mail = new TemplateMailable($this);

		if($test){
			$from = config('mail.test_email');
		}elseif($campaign){
			$from = $campaign->from;
		}else{
			$from = config('mail.email_noreply');
		}

		$mail_with = [
			'.FirstName'  => $recipient->first_name,
			'.LastName'   => $recipient->last_name,
			'.Email'      => $test ? 'arybachu@gmail.com' : $recipient->email,
			'.From'       => $from,
			'.Position'   => $recipient->position,
			'.Department' => $recipient->department,
			'.YEAR'       => date('Y'),
			'.MONTH'      => date('m'),
			'.DAY'        => date('d'),
			'.URL'        => $this->makeTrackingUrl($recipient, $campaign, $test),
		];

		$login_variables = $this->getLoginVariables();
		#Log::stack(['custom'])->debug($login_variables);

		foreach($login_variables as $login_variable){
			$url = $this->makeFakeLoginPageUrl($recipient, $campaign, $login_variable['path']);

			if(is_null($url)) continue;

			$mail_with['.'.$login_variable['variable']] = $url;
		}

		$mail->with($mail_with);


		return $mail;
	}

	public function makeTrackingUrl(Recipient $recipient, $campaign = null, $test = false){
		//$tracking_url = '';
		$campaignWithPivot = null;

		if($campaign){
			$campaignWithPivot = $recipient->campaigns()->find($campaign->id);
		}

		if($campaignWithPivot){
			$href = $campaignWithPivot->schedule->domain->url.'?rid='.$campaignWithPivot->pivot->code;

			if(!$campaignWithPivot->schedule){
				throw new \Exception('Campaign got no schedule');
			}
			if(!$campaignWithPivot->schedule->domain){
				throw new \Exception('Campaign got no domain');
			}
		}

		if($test){
			if(!$campaignWithPivot && $campaign){
				if(!$campaign->schedule){
					throw new \Exception('Campaign got no schedule');
				}
				if(!$campaign->schedule->domain){
					throw new \Exception('Campaign got no domain');
				}
				$href = $campaign->schedule->domain->url.'?rid='.md5(time());
			}
		}

		$tracking_url = env('PHISHING_MANAGER_LANDING_PAGE_URL', config('app.url').'/landingpage/');

		if(strpos($tracking_url, 'http://') !== 0 && strpos($tracking_url, 'https://') !== 0){
			$tracking_url = 'http://'.$tracking_url;
		}

		if($campaignWithPivot){
			$send_to_landing = $campaignWithPivot->schedule->send_to_landing;
			$redirect_url = $campaignWithPivot->schedule->redirect_url;

			if(intval($send_to_landing) == 0 && !empty($redirect_url)){
				$tracking_url = $redirect_url;
			}
		}

		/*$user = Auth()->user();
		if($user->send_to_landing == 0 && !empty($user->redirect_url)){
			$tracking_url = $user->redirect_url;
		}*/

		#dd($tracking_url);

		return $tracking_url;
	}

	public function makeFakeLoginPageUrl(Recipient $recipient, $campaign = null, $login_page = ''){
		//$tracking_url = '';
		$campaignWithPivot = null;

		if($campaign){
			$campaignWithPivot = $recipient->campaigns()->find($campaign->id);
			#Log::stack(['custom'])->debug($campaignWithPivot);
			if(is_null($campaignWithPivot)){
				Log::stack(['custom'])->debug('$campaignWithPivot is null');
				Log::stack(['custom'])->debug('campaign->id: '.$campaign->id);
			}
		}

		if($campaignWithPivot){
			$href = $campaignWithPivot->schedule->domain->url.'?rid='.$campaignWithPivot->pivot->code;

			if(!$campaignWithPivot->schedule){
				throw new \Exception('Campaign got no schedule');
			}
			if(!$campaignWithPivot->schedule->domain){
				throw new \Exception('Campaign got no domain');
			}
		}


		$params = $recipient->email.','.$campaign->id;
		$params = base64_encode($params);

		$tracking_url = env('PHISHING_MANAGER_LOGIN_PAGE_URL', config('app.url').'/account/'.$login_page.'?id='.$params);

		if(strpos($tracking_url, 'http://') !== 0 && strpos($tracking_url, 'https://') !== 0){
			$tracking_url = 'http://'.$tracking_url;
		}

		if($campaignWithPivot){
			$send_to_landing = $campaignWithPivot->schedule->send_to_landing; // not used
			$redirect_url = $campaignWithPivot->schedule->redirect_url;

			if(intval($send_to_landing) == 0 && !empty($redirect_url)){
				$tracking_url = $redirect_url;
			}
		}

		return $tracking_url;
	}

	public function getSubFolders($dir){
		$files   = scandir($dir);
		$folders = array();
		foreach($files as $key => $value){
			$path = realpath($dir.DIRECTORY_SEPARATOR.$value);
			if($value != "." && $value != ".." && is_dir($path)){
				$folders[] = $value;
			}
		}

		return $folders;
	}

	public function getFolderFiles($dir){
		$items = scandir($dir);
		$files = array();
		foreach($items as $key => $value){
			$path = realpath($dir.DIRECTORY_SEPARATOR.$value);
			if(!is_dir($path)){
				$files[] = $value;
			}
		}

		return $files;
	}

	public function getLoginVariables(){
		$root        = dirname(__DIR__, 2);
		$dir         = $root.'/public/account/';
		$results     = array();
		$login_pages = $this->getSubFolders($dir);
		foreach($login_pages as $login_page){
			$login_page_folders = $this->getSubFolders($dir.$login_page);
			$login_page_files   = $this->getFolderFiles($dir.$login_page);
			if(in_array('assets', $login_page_folders) && in_array('index.html', $login_page_files)){
				$results[] = array(
					'variable'    => 'login-'.$login_page,
					'path'        => $login_page,
					'description' => 'URL to fake '.strtoupper($login_page).' login page '
				);
			}
		}

		return $results;
	}

}
