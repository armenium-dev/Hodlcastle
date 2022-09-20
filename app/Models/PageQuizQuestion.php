<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PageQuizQuestion extends Model
{
    use SoftDeletes;

    public $table = 'page_quiz_questions';

    protected $dates = ['deleted_at'];

    public $fillable = [
        'answer',
        'page_quiz_id',
        'correct'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'answer',
        'page_quiz_id',
        'correct'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [];

    public function quiz()
    {
        return $this->belongsTo('App\Models\PageQuiz');
    }
}
