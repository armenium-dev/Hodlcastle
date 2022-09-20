<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Page extends Model
{
    use SoftDeletes;

    public $table = 'pages';

    protected $dates = ['deleted_at'];

    public $fillable = [
        'name',
        'language_id',
        'course_id',
        'position_id',
        'entity_id',
        'entity_type'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'name' => 'string',
        'course_id' => 'integer',
        'language_id' => 'integer',
        'position_id' => 'integer',
        'entity_id' => 'integer',
        'entity_type' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'required',
        'course_id' => 'required',
        'language_id' => 'required',
        'position_id' => 'required',
        'entity_id' => 'required',
        'entity_type' => 'required'
    ];

    public function course()
    {
        return $this->belongsTo('App\Models\Course');
    }

    public function language()
    {
        return $this->belongsTo('App\Models\Language');
    }

    public function entity()
    {
        return $this->morphTo();
    }

}
