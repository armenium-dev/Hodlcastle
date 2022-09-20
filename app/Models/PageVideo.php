<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PageVideo extends Model
{
    use SoftDeletes;

    public $table = 'page_videos';

    protected $dates = ['deleted_at'];

    public $fillable = ['url'];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [];

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

}
