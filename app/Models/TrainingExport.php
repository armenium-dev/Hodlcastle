<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TrainingExport extends Model{
	public $table = 'training_export';

	protected $dates = ['start_training', 'finish_training'];

}
