<?php

namespace App\Models;

use Carbon\Carbon;
use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;

/**
 * Class Training
 * @package App\Models
 *
 * @property integer module_id
 */
class TrainingRecipient extends Model{

	public $table = 'recipient_training';

	protected $dates = [
		'created_at',
		'updated_at',
	];

	public $fillable = [
		'training_id',
		'recipient_id',
		'code',
		'is_sent',
		'phase',
	];

	/**
	 * The attributes that should be casted to native types.
	 *
	 * @var array
	 */
	protected $casts = [
		'training_id' => 'integer',
		'recipient_id' => 'integer',
		'code' => 'string',
		'is_sent' => 'integer',
		'phase' => 'integer',
	];

	/**
	 * Validation rules
	 *
	 * @var array
	 */
	public static $rules = [
		'training_id' => 'required',
		'recipient_id' => 'required',
		'code' => 'required',
	];


}
