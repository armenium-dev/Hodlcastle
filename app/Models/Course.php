<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Course extends Model
{
    use SoftDeletes;

    public $table = 'courses';

    protected $dates = ['deleted_at'];

    public $fillable = [
        'name',
        'module_id',
        'language_id',
        'public'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'name' => 'string',
        'module_id' => 'integer',
        'language_id' => 'integer',
        'public' => 'boolean'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'module_id' => 'required',
        'language_id' => 'required'
    ];

    public function module()
    {
        return $this->belongsTo('App\Models\Module');
    }

    public function language()
    {
        return $this->belongsTo('App\Models\Language');
    }

    public function pages()
    {
        return $this->hasMany('App\Models\Page');
    }

}
