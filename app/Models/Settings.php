<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Settings extends Model{

	public $table = 'settings';
	public $timestamps = false;

	protected $dates = [];

	protected $fillable = [
		'option_name',
		'option_key',
		'option_value',
		'custom_option',
		'custom_option_page_link',
	];

}
