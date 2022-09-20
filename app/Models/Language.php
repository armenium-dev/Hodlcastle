<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Language extends Model{
	public $table = 'languages';

	public $fillable = ['code', 'name'];

	/**
	 * The attributes that should be casted to native types.
	 *
	 * @var array
	 */
	protected $casts = ['code' => 'string', 'name' => 'string'];

	/**
	 * Validation rules
	 *
	 * @var array
	 */
	public static $rules = ['code' => 'required', 'name' => 'required'];

	public function emailTemplates(){
		return $this->hasMany('App\Models\EmailTemplate');
	}

	public function courses(){
		return $this->hasMany('App\Models\Course');
	}

	public function pages(){
		return $this->hasMany('App\Models\Page');
	}

}
