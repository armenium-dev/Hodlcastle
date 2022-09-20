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
class Training extends Model
{
    use SoftDeletes;

    public $table = 'trainings';

    protected $dates = [
        'deleted_at'
    ];

    public $fillable = [
        'module_id',
        'user_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'module_id' => 'integer',
        'user_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'module_id' => 'required'
    ];

    public function groups()
    {
        return $this->belongsToMany('App\Models\Group');
    }

    public function module()
    {
        return $this->belongsTo('App\Models\Module');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function recipients()
    {
        return $this->belongsToMany('App\Models\Recipient')->withPivot(['is_sent', 'code']);
    }
}
