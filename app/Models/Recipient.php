<?php

namespace App\Models;

use Carbon\Carbon;
use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Recipient
 * @package App\Models
 * @version June 12, 2018, 11:27 am UTC
 * @property string name
 * @property string email
 * @property integer company_id
 */
class Recipient extends Model{
	use SoftDeletes;
	
	public $table = 'recipients';
	
	protected $dates = ['deleted_at'];
	
	public $fillable = [
		'first_name',
		'last_name',
		'email',
		'position',
		'department',
		'location',
		'mobile',
		'comment',
	];
	
	/**
	 * The attributes that should be casted to native types.
	 * @var array
	 */
	protected $casts = [
		'first_name' => 'string',
		'last_name'  => 'string',
		'email'      => 'string',
		'department' => 'string',
		'location'   => 'string',
		'mobile'     => 'string',
		'comment'    => 'string',
	];
	
	/**
	 * Validation rules
	 * @var array
	 */
	public static $rules = [
		'first_name' => 'required|alpha',
		'email'      => 'required|email',
	];
	
	public function groups(){
		return $this->belongsToMany('App\Models\Group');
	}
	
	public function campaigns(){
		return $this->belongsToMany('App\Models\Campaign')->withPivot('code');
	}
	
	public function trainings(){
		return $this->belongsToMany('App\Models\Training')->withPivot('code', 'is_sent', 'phase', 'created_at');
	}
	
	public function results(){
		return $this->hasMany('App\Models\Result');
	}
	
	public function scopeActive($q){
		return $q->where('is_hidden', 0);
	}
	
	public function getFullNameAttribute(){
		return $this->first_name.' '.$this->last_name;
	}
	
	public function attachToCampaign($campaign){
		$this->campaigns()->attach($campaign, ['code' => hash2IntsTime($campaign->id, $this->id)]);
	}
	
	public function attachToTraining($training){
		$this->trainings()->attach($training, [
			'code' => hash2IntsTime($training->id, $this->id),
			'created_at' => Carbon::now(),
			'updated_at' => Carbon::now(),
		]);
	}
	
	public function setIsSentToCampaign($campaign){
		$this->campaigns()->sync([$campaign->id => ['is_sent' => 1]], false);
	}
	
	public function setIsSentToTraining($training){
		$this->trainings()->sync([$training->id => ['is_sent' => 1]], false);
	}

	public function setPhaseToTraining($training, $phase = 1){
		$this->trainings()->sync([$training->id => ['phase' => $phase]], false);
	}
}
