<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property string $name
 * @property string $description
 * @property int $language_id
 * @property int $is_active
 * @property string $campaign_name
 * @property int $email_template_id
 * @property int $domain_id
 * @property int $is_short
 * @property int $send_to_landing
 * @property string $redirect_url
 * @property int $created_by_user_id
 * @property int $updated_by_user_id
 * @property int $deleted_by_user_id
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 */
class Scenario extends Model{
	use SoftDeletes;

	public $table = 'scenarios';

	protected $dates = ['deleted_at'];

    protected $fillable = [
	    'name',
	    'description',
	    'language_id',
	    'is_active',
	    'campaign_name',
	    'email_template_id',
	    'domain_id',
	    'email',
	    'is_short',
	    'send_to_landing',
	    'redirect_url',
	    'with_attach',
	    'created_by_user_id',
	    'updated_by_user_id',
	    'deleted_by_user_id',
	    'created_at',
	    'updated_at',
	    'deleted_at'
    ];

	/**
	 * The attributes that should be casted to native types.
	 * @var array
	 */
	protected $casts = [
		'name' => 'string',
		'language_id' => 'integer',
	];

	/**
	 * Validation rules
	 * @var array
	 */
	public static $rules = [
		'name' => 'required',
		'campaign_name' => 'required',
		'email' => 'email:rfc,dns',
	];

	public function image(){
		return $this->morphOne('App\Models\Image', 'imageable')->latest();
	}

	public function language(){
		return $this->belongsTo('App\Models\Language');
	}

	public function user(){
		return $this->belongsTo('App\User');
	}

	public function created_by_user(){
		return $this->belongsTo('App\User');
	}

	public function updated_by_user(){
		return $this->belongsTo('App\User');
	}

	public function deleted_by_user(){
		return $this->belongsTo('App\User');
	}

	public function email_template(){
		return $this->belongsTo('App\Models\EmailTemplate');
	}

	public function domain(){
		return $this->belongsTo('App\Models\Domain');
	}

}
