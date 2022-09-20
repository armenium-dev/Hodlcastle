<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Landing
 * @package App\Models
 * @version June 28, 2018, 12:06 pm UTC
 *
 * @property integer company_id
 * @property string name
 * @property string content
 * @property integer redirect
 * @property integer capture_credentials
 */
class Landing extends Model
{
    use SoftDeletes;

    public $table = 'landings';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'company_id',
        'name',
        'content',
        'styles',
        'redirect',
        'capture_credentials'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'company_id' => 'integer',
        'name' => 'string',
        'content' => 'string',
        'redirect' => 'integer',
        'capture_credentials' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'company_id' => 'required|numeric',
        'name' => 'required',
    ];

    public function company()
    {
        return $this->belongsTo('App\Models\Company');
    }

    public function campaigns()
    {
        return $this->hasMany('App\Models\Campaign');
    }

    public function scopeDefault($q)
    {
        return $q->whereName('default');
    }

    public function getContentRawAttribute()
    {
        $html = $this->content;
        $html = str_replace('<p>', '', $html);
        $html = str_replace('</p>', '', $html);

        return $html;
    }
}
