<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Supergroup
 * @package App\Models
 * @version October 16, 2018, 11:59 am UTC
 *
 * @property string name
 */
class Supergroup extends Model
{
    use SoftDeletes;

    public $table = 'supergroups';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'name',
        //'schedule_id',
        'captain_id',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'name' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'required'
    ];

    public function companies()
    {
        return $this->belongsToMany('App\Models\Company')
            ->withPivot('group_ids')
        ;
    }

    public function schedules()
    {
        return $this->belongsToMany('App\Models\Schedule');
    }

    public function campaigns()
    {
        return $this->hasMany('App\Models\Campaign');
    }
}
