<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TrainingStatistic extends Model{
	use SoftDeletes;

	public $table = 'training_statistics';

	protected $dates = ['deleted_at'];

	public $fillable = [
		'recipient_id',
		'company_id',
		'code',
		'notify_training',
		'total_answers',
		'correct_answers',
		'wrong_answers',
		'user_score',
		'answers_data',
	];

	/**
	 * The attributes that should be casted to native types.
	 *
	 * @var array
	 */
	protected $casts = [
		'recipient_id' => 'integer',
		'company_id' => 'integer',
		'code' => 'string',
		'total_questions' => 'integer',
		'correct_answers' => 'integer',
		'wrong_answers' => 'integer',
		'user_score' => 'integer',
		'answers_data' => 'json',
	];

	/**
	 * Validation rules
	 *
	 * @var array
	 */
	public static $rules = [
		'recipient_id' => 'required',
		'company_id' => 'required',
		'code' => 'required'
	];

	public function recipient(){
		return $this->belongsTo('App\Models\Recipient');
	}

	public function company(){
		return $this->belongsTo('App\Models\Company');
	}

	public function timeSpend(){
		$diff = '';

		if($this->start_training && $this->finish_training){
			$from = new Carbon($this->start_training);
			$till = new Carbon($this->finish_training);
			$diff = $till->diffInMinutes($from).' min';
		}

		return $diff;
	}
}
