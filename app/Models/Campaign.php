<?php

namespace App\Models;

use Carbon\Carbon;
use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Mail;

/**
 * Class Campaign
 * @package App\Models
 * @version July 7, 2018, 7:11 am UTC
 * @property string name
 * @property integer email_template_id
 * @property integer landing_page_id
 * @property integer domain
 * @property integer group_id
 */
class Campaign extends Model{
	use SoftDeletes;

	const STATUS_INACTIVE = 0;
	const STATUS_ACTIVE = 1;
	const STATUS_COMPLETED = 2;

	CONST TYPE_SEND_NOW = 0;
	CONST TYPE_SCHEDULED = 1;

	public $table = 'campaigns';

	public static $short_link = 0;
	public static $with_attachment = 0;
	public static $recipiend_code = '';
	public static $domain = '';

	protected $dates = [
		'deleted_at',
		'completed_at',
		'schedule_start',
		'schedule_end',
	];

	public $fillable = [
		'name',
		//'email_template_id',
		//'landing_id',
		//'domain_id',
		//'group_id',
		'company_id',
		'user_agent',
		//'schedule_start',
		//'schedule_end',
		//'time_start',
		//'time_end',
		//'send_weekend',
		'schedule_id',
		'supergroup_id',
		'status',
		'scheduled_type',
		'user_id',
		'email',
		'is_short',
		'with_attach',
		'is_kickoff'
	];

	/**
	 * The attributes that should be casted to native types.
	 * @var array
	 */
	protected $casts = [
		'name'  => 'string',
		//'email_template_id' => 'integer',
		///'landing_id' => 'integer',
		//'domain_id' => 'integer',
		//'group_id' => 'integer',
		'email' => 'string'
	];

	/**
	 * Validation rules
	 * @var array
	 */
	public static $rules = [
		'name'  => 'required_if:schedule.email_template_id,1',
		//        'company_id' => 'required|numeric',
		//        'email_template_id' => 'required|numeric',
		//        'landing_id' => 'required|numeric',
		//        'domain_id' => 'required|numeric',
		//        'group_id' => 'required|numeric',
		'email' => 'required|email:rfc,dns',
	];

	public function company(){
		return $this->belongsTo('App\Models\Company');
	}

	public function supergroup(){
		return $this->belongsTo('App\Models\Supergroup');
	}

	public function schedule(){
		return $this->belongsTo('App\Models\Schedule');
	}

	public function groups(){
		return $this->belongsToMany('App\Models\Group');
	}

	public function recipients(){
		return $this->belongsToMany('App\Models\Recipient')->withPivot(['is_sent', 'code']);
	}

	public function events(){
		return $this->hasMany('App\Models\Event');
	}

	public function results(){
		return $this->hasMany('App\Models\Result');
	}

	public function user(){
		return $this->belongsTo('App\User');
	}

	public function getStatusCalcAttribute(){
		$out = $this->status;

		if($this->schedule){
			$schedule_end = Carbon::parse($this->schedule->schedule_end);
			//var_dump($this->status != self::STATUS_INACTIVE, $this->schedule->schedule_end, $schedule_end->isPast(), $this->status);
		}

		if($this->schedule && $this->status != self::STATUS_INACTIVE){
			#$schedule_start = Carbon::parse($this->schedule->schedule_start->format('Y-m-d').' '.$this->schedule->time_start);
			$schedule_start = Carbon::parse($this->schedule->schedule_start);
			$schedule_end   = Carbon::parse($this->schedule->schedule_end);

			if($schedule_start->isFuture()){
				$out = self::STATUS_INACTIVE;
				#Log::stack(['custom'])->debug([$this->id, $schedule_start]);
			}
			if($schedule_end->isPast()){
				$out = self::STATUS_COMPLETED;
			}
		}

		return $out;
	}

	public function getStatusCalcTitleAttribute(){
		return $this->getStatusCalcTitle($this->statusCalc);
	}

	protected function getStatusCalcTitle($id){
		$out = [
			self::STATUS_INACTIVE  => 'Inactive',
			self::STATUS_ACTIVE    => 'Active',
			self::STATUS_COMPLETED => 'Completed',
		];

		return $out[$id];
	}

	public function setInactive(){
		$this->status = self::STATUS_INACTIVE;
	}

	public function countResults($param){
		return $this->results()->type($param)->count();
	}

    public function countResultsOnly($param) {
        $res = $this->results()->typeOnly($param)->get()->count();
        return $res ?? 0;
    }

	public function clicks(){
		$collections = $this->results()->type('click')->get()->groupBy(function($date){
				return Carbon::parse($date->created_at)->format('w');
			});
		$out         = [];

		foreach($collections as $w => $collection){
			$out[$w] = $collection->count();
		}

		for($i = 0; $i < 7; $i++){
			if(!isset($out[$i])){
				$out[$i] = 0;
			}
		}

		ksort($out);

		return $out;
	}

	public function resultsMarkers(){
		$arr = $this->results()->where('lat', '!=', '')->where('lng', '!=', '')->get()->toArray();

		foreach($arr as $k => $item){
			$arr[$k] = array_only($item, ['lat', 'lng', 'type']);
		}

		return json_encode($arr);
	}

	public function getIsCompletedAttribute(){
		return $this->status_calc == self::STATUS_COMPLETED;
	}

	public function getIsActiveAttribute(){
		return $this->status_calc == self::STATUS_ACTIVE;
	}

	public function getBrowsersAttribute(){
		return $this->results()->select('user_agent', \DB::raw('count(*) as total'))->groupBy('user_agent')->get();
	}

	public function getResultsDataChartAttribute(){
		$records = $this->results()->select('type_id', \DB::raw('count(*) as total'))->groupBy('type_id')->get();

		$out = [];

		$titles = Result::sTypeTitles();

		foreach($titles as $k => $title){
			$out[$title] = 0;
		}

		foreach($records as $record){
			$out[$titles[$record['type_id']]] = $record['total'];
		}


		return $out;
	}

	public function getFromAttribute(){
		$from = $this->exists && $this->schedule && $this->schedule->domain ? $this->schedule->domain->email : 'arybachu@verifysecurity.nl';

		return $from;
	}


	public function sendToAllRecipientsByCron(){
		#Log::stack(['custom'])->debug($this->id);
		#Log::stack(['custom'])->debug($this->groups);

		foreach($this->groups as $group){
			$recipients = $group->recipients()->whereNotIn('id', $this->recipients->pluck('id'))->get();
			#Log::stack(['custom'])->debug($group->id);
			#Log::stack(['custom'])->debug($recipients);

			foreach($recipients as $recipient){
				if($recipient){
					if($this->schedule->email_template_id){
						#Log::stack(['custom'])->debug($this->id);
						#Log::stack(['custom'])->debug($this->schedule->id);
						#Log::stack(['custom'])->debug($this->schedule->emailTemplate->id);
                        $recipient->attachToCampaign($this);
						$this->schedule->emailTemplate->send($recipient, $this);
					}elseif($this->schedule->sms_template_id){
					    if (!$recipient->mobile){
					        continue;
                        }

						$recipient->attachToCampaign($this);
						$this->setRecipendCode($recipient, $this->id);
						$this->schedule->smsTemplate->send($recipient, $this);
					}

					$recipient->setIsSentToCampaign($this);

					return true;
				}else{
					// if no recipient to send - proceed to next group
					Log::stack(['custom'])->debug('no recipient to send - proceed to next group');

					return false;
				}
			}
		}

		return false;
	}

	/**
	 * OLD
	 * Not used.
	 * sendToAllRecipients used after campaign created
	 **/
	public function sendToNextRecipient(){

		foreach($this->groups as $group){
			$recipient = $group->recipients()->whereNotIn('id', $this->recipients->pluck('id'))->first();
			#Log::stack(['custom'])->debug($recipient);

			if($recipient){
				if($this->schedule->email_template_id){
					$this->schedule->emailTemplate->send($recipient, $this);
				}elseif($this->schedule->sms_template_id){
					$recipient->attachToCampaign($this);
					$this->setRecipendCode($recipient, $this->id);
					$this->schedule->smsTemplate->send($recipient, $this);
				}

				$recipient->setIsSentToCampaign($this);

				return true;
			}else{
				// if no recipient to send - proceed to next group
				Log::stack(['custom'])->debug('no recipient to send - proceed to next group');
				return false;
			}
		}

		return false;
	}

	public function sendToAllRecipients(){
		$this->setShort($this->is_short);
		$this->setWithAttachment($this->with_attach);
		$this->setDomain($this->schedule->domain->domain);

		foreach($this->groups as $group){
			$recipients = $group->recipients()->whereNotIn('id', $this->recipients->pluck('id'))->get();

			foreach($recipients as $recipient){
				$recipient->attachToCampaign($this);
				$this->setRecipendCode($recipient, $this->id);
				$this->schedule->emailTemplate->send($recipient, $this);
				$recipient->setIsSentToCampaign($this);
			}
		}

		$this->sendToCapitanAboutCompanyTrialMode();
	}

	public function sendToCapitanAboutCompanyTrialMode(){


		if(Auth::check() && Auth::user()->hasRole('customer')){
			if($this->company->is_trial){
				$content = 'Trial company: '.$this->company->name;

				Mail::send('emails.send', ['title' => 'title', 'content' => $content], function($message){
					$from    = config('mail.email_noreply');
					$to      = config('mail.support_email');
					$subject = 'Trial user: '.Auth::user()->name.' sent email campaign.';

					$message->from($from, $from);
					$message->to($to);
					$message->subject($subject);

					$message->getHeaders()->addTextHeader('X-No-Track', Str::random(10));
				});
			}
		}
		#dd($this->company->id, $this->company->is_trial, $this->user->id, Auth::user());
	}

	/**
	 * For captain
	 */
	public function sendSMSToAllRecipients(){

		$tmp = [];

		foreach($this->groups as $group){
			$recipients = $group->recipients()->whereNotIn('id', $this->recipients->pluck('id'))->get();

			foreach($recipients as $recipient){
				$recipient->attachToCampaign($this);
				$this->setRecipendCode($recipient, $this->id);
				$this->schedule->smsTemplate->send($recipient, $this);
				$recipient->setIsSentToCampaign($this);
			}
		}

		return $tmp;
	}

	public function sendToCapitanAboutSmishingCampaign($create = true){
		if(env('APP_ENV') == 'local') return;

		$campaign = $this;

		#if(Auth::check() && Auth::user()->hasRole('customer')){

			$subject = $create ? 'New smishing campaign' : 'Smishing campaign has been removed';
			$content = [];
			$content[] = '<b>Campaign details</b>';
			$content[] = 'Removed At: '.Carbon::now()->format('j F, Y H:i');
			$content[] = 'ID: '.$campaign->id;
			$content[] = 'Name: '.$campaign->name;
			$content[] = 'Type: SMS';
			$content[] = 'Template: '.$campaign->schedule->smsTemplate->name;
			if($create){
				#$content[] = 'Content: '.$campaign->schedule->smsTemplate->content;
				$content[] = 'Schedule type: '.$campaign->scheduled_type;
				$content[] = 'Schedule start: '.$campaign->schedule->schedule_start->format('j F, Y').' '.$campaign->schedule->time_start;
				$content[] = 'Schedule end: '.$campaign->schedule->schedule_end->format('j F, Y').' '.$campaign->schedule->time_end;
				$content[] = 'Link: <a href="'.URL::to('/campaigns/'.$campaign->id).'">View</a>';
			}
			$content = implode('<br>', $content);

			Mail::send('emails.send', ['title' => 'title', 'content' => $content], function($message) use ($campaign, $subject){
				if($campaign && $campaign->email){
					$from = $campaign->email;
				}else{
					$from = config('mail.email_noreply');
				}

				$message->from($from, $from);
				$message->to(config('mail.email_service_desk'));
				$message->subject($subject);

				$message->getHeaders()->addTextHeader('X-No-Track', Str::random(10));
			});
		#}
	}

	public function setShort($status){
		self::$short_link = $status;
	}

	public static function getShort(){
		return self::$short_link;
	}

	public function setWithAttachment($status){
		self::$with_attachment = $status;
	}

	public function setDomain($domain){
		self::$domain = $domain;
	}

	public static function getWithAttachment(){
		return self::$with_attachment;
	}

	public static function getDomain(){
		return self::$domain;
	}

	public static function setRecipendCode($recipient, $id){
		$campaignWithPivot = $recipient->campaigns()->find($id);
		if($campaignWithPivot){
			self::$recipiend_code = $campaignWithPivot->pivot->code;
		}
	}

	public static function getRecipiendCode(){
		return self::$recipiend_code;
	}
}
