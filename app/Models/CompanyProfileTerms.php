<?php

namespace App\Models;

use Eloquent as Model;
use Event;

/**
 * Class CompanyProfileTerms
 * @package App\Models
 * @property string name
 */
class CompanyProfileTerms extends Model{
	
	public $table = 'company_profile_terms';

	public $fillable = [
		'name',
		'slug',
	];
	
	/**
	 * Validation rules
	 * @var array
	 */
	public static $rules = [
		'name'       => 'required',
		'slug' => 'required',
	];

	public function rules(){
		return $this->hasMany('App\Models\CompanyProfileRules');
	}

	public function profiles(){
		return $this->belongsToMany(CompanyProfiles::class, 'company_profile_rules', 'term_id', 'profile_id');
	}

}
