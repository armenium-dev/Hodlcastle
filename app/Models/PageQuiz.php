<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PageQuiz extends Model
{
    use SoftDeletes;

    public $table = 'page_quizzes';

    protected $dates = ['deleted_at'];

    public $fillable = [
        'name',
        'type',
        'text'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'name',
        'type',
        'text'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [];

    public function page()
    {
        return $this->morphMany('App\Models\Page', 'entity');
    }

    public function questions()
    {
        return $this->hasMany('App\Models\PageQuizQuestion');
    }
}
