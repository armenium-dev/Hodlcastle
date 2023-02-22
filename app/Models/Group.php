<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Group
 * @package App\Models
 * @version June 23, 2018, 6:42 pm UTC
 *
 * @property string name
 * @property integer company_id
 */
class Group extends Model{

	use SoftDeletes;

	public $table = 'groups';

	protected $dates = ['deleted_at'];

	public $fillable = [
		'name',
		'company_id'
	];

	/**
	 * The attributes that should be casted to native types.
	 *
	 * @var array
	 */
	protected $casts = [
		'name' => 'string',
		'company_id' => 'integer'
	];

	/**
	 * Validation rules
	 *
	 * @var array
	 */
	public static $rules = [
		'name' => 'required',
//		'company_id' => 'required|numeric'
	];

	public function company(){
		return $this->belongsTo('App\Models\Company');
	}

	public function recipients(){
		return $this->belongsToMany('App\Models\Recipient');
	}

	public function campaigns(){
		return $this->belongsToMany('App\Models\Campaign');
	}

	public function trainings(){
		return $this->belongsToMany('App\Models\Training');
	}

	public function sendToRecipients($campaign, $emailTemplate){
		foreach($this->recipients as $recipient){
			$recipient->campaigns()->attach($campaign, ['code' => hash2IntsTime($campaign->id.$recipient->id)]);
			$emailTemplate->send($recipient, $campaign);
		}
	}
}
