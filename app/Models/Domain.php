<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Domain
 * @package App\Models
 * @version June 12, 2018, 6:12 am UTC
 *
 * @property string name
 */
class Domain extends Model
{
    use SoftDeletes;

    public $table = 'domains';

    protected $dates = ['deleted_at'];

    public $fillable = [
        'name',
        'domain',
        'company_id',
        'is_public'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'name' => 'string',
        'domain' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'required',
        'domain' => 'required'
    ];

    public function company()
    {
        return $this->belongsTo('App\Models\Company');
    }

    public function campaigns()
    {
        return $this->hasMany('App\Models\Campaign');
    }
}
