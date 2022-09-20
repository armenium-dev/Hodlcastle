<?php

namespace App\Models;

use Carbon\Carbon;
use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Schedule
 * @package App\Models
 * @version October 17, 2018, 10:53 am UTC
 * @property integer campaign_id
 * @property integer supergroup_id
 * @property integer email_template_id
 * @property integer sms_template_id
 * @property integer landing_id
 * @property integer domain_id
 * @property date schedule_start
 * @property date schedule_end
 */
class Schedule extends Model{
	use SoftDeletes;
	
	const DATE_RANGE_FORMAT = 'd/m/Y';
	const DATE_RANGE_SEPAR = ' - ';
	
	public $table = 'schedules';
	
	
	protected $dates = ['deleted_at'];
	
	
	public $fillable = [
		'supergroup_id',
		'email_template_id',
		'sms_template_id',
		'landing_id',
		'domain_id',
		'schedule_start',
		'schedule_end',
		'time_start',
		'time_end',
		'send_weekend',
		'redirect_url',
		'send_to_landing',
	];
	
	/**
	 * The attributes that should be casted to native types.
	 * @var array
	 */
	protected $casts = [
		'supergroup_id'     => 'integer',
		'email_template_id' => 'integer',
		'sms_template_id'   => 'integer',
		'landing_id'        => 'integer',
		'domain_id'         => 'integer',
		'schedule_start'    => 'date',
		'schedule_end'      => 'date',
		'time_start'        => 'time',
		'time_end'          => 'time',
	];
	
	/**
	 * Validation rules
	 * @var array
	 */
	public static $rules = [
		'supergroup_id'     => 'required',
		'email_template_id' => 'required',
		'sms_template_id'   => 'required',
		//'landing_id' => 'required',
		'domain_id'         => 'required',
		// not required because campaign can be sent now
		//        'schedule_start' => 'required',
		//        'schedule_end' => 'required',
		//        'time_start' => 'required',
		//        'time_end' => 'required',
		//        'send_weekend' => 'required',
	];
	
	/**
	 * Validation rules
	 * Campaign::TYPE_SCHEDULED = 1
	 * @var array
	 */
	public static $rules_relation = [
		'schedule.schedule_range'    => 'required_if:scheduled_type,1',
		"schedule.email_template_id" => "required",
		"schedule.sms_template_id"   => "required",
		//"schedule.landing_id" => "required",
		"schedule.domain_id"         => "required",
		"schedule.time_start"        => 'required_if:scheduled_type,1',
		#"schedule.time_end"          => 'required_if:scheduled_type,1',
		//"schedule.send_weekend" => 'required_if:scheduled_type,1',
	];
	
	public function supergroups(){
		return $this->belongsToMany('App\Models\Supergroup');
	}
	
	public function campaigns(){
		return $this->hasMany('App\Models\Campaign');
	}
	
	public function emailTemplate(){
		return $this->belongsTo('App\Models\EmailTemplate');
	}
	
	public function smsTemplate(){
		return $this->belongsTo('App\Models\SmsTemplate');
	}
	
	public function landing(){
		return $this->belongsTo('App\Models\Landing');
	}
	
	public function domain(){
		return $this->belongsTo('App\Models\Domain');
	}
	
	/*public static function boot(){
		self::creating(function($model){
			if(!isset($input['schedule']['send_weekend'])){
				$input['schedule']['send_weekend'] = 0;
			}
			
			$input['schedule'] = $this->fillDates($input['schedule']);
		});
	}*/
	
	public function setScheduleRange($schedule_str){
		if($schedule_str == ''){
			return;
		}
		
		$date_ranges = explode(self::DATE_RANGE_SEPAR, $schedule_str);
		
		$this->schedule_start = Carbon::createFromFormat(self::DATE_RANGE_FORMAT, $date_ranges[0]);
		$this->schedule_end   = Carbon::createFromFormat(self::DATE_RANGE_FORMAT, $date_ranges[1]);
	}
}
