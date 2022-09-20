<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class DomainWhitelist
 * @package App\Models
 *
 * @property string domain
 * @property integer company_id
 */
class DomainWhitelist extends Model
{
    use SoftDeletes;

    public $table = 'domain_whitelists';


    protected $dates = ['deleted_at'];


    public $fillable = [
        'domain',
        'company_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'domain' => 'string',
        'company_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
//        'domain' => 'required',
//        'company_id' => 'required|numeric'
    ];

    public function company()
    {
        return $this->belongsToMany('App\Models\Company');
    }
}
