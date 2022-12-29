<?php

namespace App\Models;

use Eloquent as Model;
use Event;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * Class CompanyProfiles
 * @package App\Models
 * @property string name
 */
class CompanyProfileRules extends Pivot{
	
	public $table = 'company_profile_rules';
	public $timestamps = false;
	public $fillable = [
		'profile_id',
		'term_id',
		'active',
	];
	
	public function profiles(){
		return $this->belongsTo('App\Models\CompanyProfiles');
	}

	public function terms(){
		return $this->belongsTo('App\Models\CompanyProfileTerms');
	}

}
