<?php

namespace App\Models;

use Carbon\Carbon;
use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;

/**
 * Class Training
 * @package App\Models
 *
 * @property integer module_id
 */
class Training extends Model{
	use SoftDeletes;

	public $table = 'trainings';

	protected $dates = [
		'deleted_at'
	];

	public $fillable = [
		'module_id',
		'user_id',
		'start_template_id',
		'finish_template_id',
		'notify_template_id',
	];

	/**
	 * The attributes that should be casted to native types.
	 *
	 * @var array
	 */
	protected $casts = [
		'module_id' => 'integer',
		'user_id' => 'integer',
		'start_template_id' => 'integer',
		'finish_template_id' => 'integer',
		'notify_template_id' => 'integer',
	];

	/**
	 * Validation rules
	 *
	 * @var array
	 */
	public static $rules = [
		'module_id' => 'required',
		'start_template_id' => 'required',
		'finish_template_id' => 'required',
		'notify_template_id' => 'required',
	];

	public function groups(){
		return $this->belongsToMany('App\Models\Group');
	}

	public function module(){
		return $this->belongsTo('App\Models\Module');
	}

	public function template(){
		return $this->belongsTo('App\Models\TrainingNotifyTemplate');
	}

	public function user(){
		return $this->belongsTo('App\User');
	}

	public function recipients(){
		return $this->belongsToMany('App\Models\Recipient')->withPivot(['is_sent', 'code']);
	}

	public function getTemplateByType($type){
		$template = new TrainingNotifyTemplate();

		switch($type){
			case "start":
				$id = $this->start_template_id;
				break;
			case "finish":
				$id = $this->finish_template_id;
				break;
			case "remind":
				$id = $this->notify_template_id;
				break;
			default:
				$id = 0;
				break;
		}

		if($id > 0){
			$template = TrainingNotifyTemplate::whereId($id)->first();
		}

		return $template;
	}

}
