<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Options extends Model{

	public $table = 'options';
	public $timestamps = false;

	protected $dates = [];

	protected $fillable = [
		'option_key',
		'option_value'
	];

}
