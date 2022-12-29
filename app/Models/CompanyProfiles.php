<?php

namespace App\Models;

use Eloquent as Model;
use Event;

/**
 * Class CompanyProfiles
 * @package App\Models
 * @property string name
 */
class CompanyProfiles extends Model{

	public $table = 'company_profiles';

	public $fillable = [
		'name',
	];

	/**
	 * Validation rules
	 * @var array
	 */
	public static $rules = [
		'name' => 'required',
	];

	public function rules(){
		return $this->hasMany('App\Models\CompanyProfileRules');
	}

	public function companies(){
		return $this->hasMany('App\Models\Company');
	}

	public function terms(){
		return $this->belongsToMany(CompanyProfileTerms::class, 'company_profile_rules', 'profile_id', 'term_id');
	}
}
