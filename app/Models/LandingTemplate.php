<?php

namespace App\Models;

use App;
use Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Mail;
use URL;
use Config;

/**
 * Class LandingTemplate
 * @package App\Models
 * @property string lang
 * @property string tags
 */
class LandingTemplate extends Model {

	use SoftDeletes;

	public $table = 'landing_templates';

	protected $dates = ['deleted_at'];

	/**
	 * The attributes that could be used in mass assignment.
	 * @var array
	 */
	protected $fillable = [
		'company_id',
		'is_public',
		'name',
		'content',
		'uuid',
		'url',
		'options',
	];

	/**
	 * The attributes that should be casted to native types.
	 * @var array
	 */
	protected $casts = [
		'company_id' => 'integer',
		'is_public' => 'integer',
		'name' => 'string',
		'content' => 'string',
		'uuid' => 'string',
		'url' => 'string',
	];

	/**
	 * Validation rules
	 * @var array
	 */
	public static $rules = [
		'name' => 'required',
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
}
