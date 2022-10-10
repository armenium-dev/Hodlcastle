<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Leaderboard extends Model{
	public $table = 'leaderboard';

	protected $dates = ['send_date'];

}
