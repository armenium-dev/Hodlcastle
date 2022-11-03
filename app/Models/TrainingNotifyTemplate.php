<?php namespace App\Models;

use App;
use App\User;
use Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Mail;
use URL;
use Config;
use App\DynamicMail\Facades\DynamicMail;
use Illuminate\Support\Facades\Log;

/**
 * Class TrainingNotifyTemplate
 * @package App\Models
 * @property string lang
 * @property string tags
 */
class TrainingNotifyTemplate extends Model{
	
	use SoftDeletes;
	
	const TYPE_START = 1;
	const TYPE_END = 2;
	const TYPE_REMIND = 3;

	public $table = 'training_notify_templates';
	
	protected $dates = ['deleted_at'];
	
	/**
	 * The attributes that could be used in mass assignment.
	 * @var array
	 */
	protected $fillable = [
		'company_id',
		'module_id',
		'type_id',
		'language_id',
		'is_public',
		'name',
		'subject',
		'content',
	];
	
	/**
	 * The attributes that should be casted to native types.
	 * @var array
	 */
	protected $casts = [
		'company_id' => 'integer',
		'module_id' => 'integer',
		'type_id' => 'integer',
		'language_id' => 'integer',
		'is_public' => 'integer',
		'name'        => 'string',
		'subject'     => 'string',
		'content'        => 'string',
	];
	
	/**
	 * Validation rules
	 * @var array
	 */
	public static $rules = [
		'name'    => 'required',
		'subject' => 'required',
	];

	public $types = [
		self::TYPE_START => 'Training Start',
		self::TYPE_END => 'Training Finish',
		self::TYPE_REMIND => 'Training Reminder',
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

	public function module(){
		return $this->belongsTo('App\Models\Module');
	}

	public function type(){
		return $this->types[$this->type_id];
	}

	public function getTypes(){
		return $this->types;
	}
}
