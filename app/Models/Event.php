<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Event
 * @package App\Models
 * @version July 25, 2018, 2:48 pm UTC
 *
 * @property integer campaign_id
 * @property string email
 * @property timestamp time
 * @property string message
 */
class Event extends Model
{
    use SoftDeletes;

    public $table = 'events';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'campaign_id',
        'email',
        'phone',
        'time',
        'message'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'campaign_id' => 'integer',
        'email' => 'string',
        'phone' => 'string',
        'message' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'campaign_id' => 'required|numeric',
        'email' => 'required|email',
        'time' => 'required',
        'message' => 'required|alpha',
    ];

    public function campaign()
    {
        return $this->belongsTo('App\Models\Campaign');
    }
}
