<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Module extends Model
{
    use SoftDeletes;

    public $table = 'modules';

    protected $dates = ['deleted_at'];

    public $fillable = [
        'name',
        'public'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'name' => 'string',
        'public' => 'boolean'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'required'
    ];

    public function courses()
    {
        return $this->hasMany('App\Models\Course');
    }

    public function trainings()
    {
        return $this->hasMany('App\Models\Training');
    }

}
