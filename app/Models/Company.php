<?php

namespace App\Models;

use App\Events\SoftLimitExceededEvent;
use Carbon\Carbon;
use Eloquent as Model;
use Event;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Company
 * @package App\Models
 * @version June 12, 2018, 6:11 am UTC
 * @property string name
 */
class Company extends Model{
	
	use SoftDeletes;
	
	const DATE_FORMAT = 'd/m/Y';
	
	public $table = 'companies';
	
	
	protected $dates = ['deleted_at', /*'expires_at'*/];
	
	
	public $fillable = [
		'name',
		'status',
		'expires_at',
		'soft_limit',
		'max_recipients',
		'is_trial',
		'smishing',
	];
	
	public $appends = ['recipients_capacity'];
	
	/**
	 * The attributes that should be casted to native types.
	 * @var array
	 */
	protected $casts = [
		'name'           => 'string',
		'status'         => 'integer',
		'soft_limit'     => 'integer',
		'max_recipients' => 'integer',
		'is_trial'       => 'boolean',
		'smishing'       => 'boolean',
		//'expires_at' => 'date:d-m-Y H:i:s'
	];
	
	/**
	 * Validation rules
	 * @var array
	 */
	public static $rules = [
		'name'       => 'required',
		'expires_at' => 'required',
	];
	
	public function campaigns(){
		return $this->hasMany('App\Models\Campaign');
	}
	
	public function domains(){
		return $this->hasMany('App\Models\Domain');
	}
	
	public function landings(){
		return $this->hasMany('App\Models\Landing');
	}
	
	public function groups(){
		return $this->hasMany('App\Models\Group');
	}
	
	public function domain_whitelists(){
		return $this->belongsToMany('App\Models\DomainWhitelist');
	}
	
	public function supergroups(){
		return $this->belongsToMany('App\Models\Supergroup')->withPivot('group_ids');
	}
	
	public function logo(){
		return $this->morphOne('App\Models\Image', 'imageable')->orderBy('id', 'DESC');
	}
	
	public static function boot(){
		self::creating(function($model){
			$model->expires_at = Carbon::now()->addYear();
			#dd($model->expires_at); exit;
		});
	}
	
	public function getRecipients(){
		$recipients = [];
		
		$groups = $this->groups()->whereHas('recipients', function($q){
			$q->active();
		})->get();
		
		foreach($groups as $group){
			$recipients = array_merge($recipients, $group->recipients->toArray());
		}
		
		$has    = [];
		$output = [];
		
		foreach($recipients as $data){
			if(!in_array($data['email'], $has)){
				$has[]    = $data['email'];
				$output[] = $data;
			}
		}
		
		return collect($output);
	}
	
	public function getAllRecipients(){
		$recipients = [];
		
		foreach($this->whereNull('deleted_at')->get() as $item){
			$groups = $item->groups()->whereHas('recipients', function($q){
				$q->active();
			})->get();
			
			foreach($groups as $group){
				$recipients = array_merge($recipients, $group->recipients->toArray());
			}
		}
		
		$has    = [];
		$output = [];
		
		foreach($recipients as $data){
			if(!in_array($data['email'], $has)){
				$has[]    = $data['email'];
				$output[] = $data;
			}
		}
		
		return collect($output);
	}
	
	public function getRecipientsCapacityAttribute(){
		$max_recipients   = $this->max_recipients;
		$recipients_count = $this->getRecipients()->count();
		
		return $max_recipients - $recipients_count;
	}
	
	public function getAllRecipientsCapacityAttribute(){
		$max_recipients = 0;
		foreach($this->whereNull('deleted_at')->get() as $item){
			$max_recipients += $item->max_recipients;
		}
		
		$recipients_count = $this->getAllRecipients()->count();
		
		return $max_recipients - $recipients_count;
	}
	
	public function posts(){
		return $this->hasManyThrough('App\Post', 'App\User');
	}
	
	public function getExpiredAttribute(){
		return Carbon::parse($this->expires_at)->isPast();
	}
	
	public function getActiveAttribute(){
		return $this->status != 0;
		//return !$this->expired && $this->status != 0;
	}
	
	public function checkSoftLimit(){
		if($this->soft_limit > 0 && $this->soft_limit <= $this->getRecipients()->count()){
			Event::fire(new SoftLimitExceededEvent($this));
		}
	}
	
	public function checkCapacity(array $recipients_attrs = [], $group = null){
		$count_in_the_group = 0;
		
		if($this->max_recipients > 0){
			$emails = array_pluck($recipients_attrs, 'email');
			
			if($group instanceof Group){
				$count_in_the_group = $group->recipients()->whereIn('email', $emails)->count();
			}
			$recipients_capacity = $this->recipientsCapacity - (count($recipients_attrs) - $count_in_the_group);
			
			return $recipients_capacity >= 0;
		}else{
			return true;
		}
		
	}
}
