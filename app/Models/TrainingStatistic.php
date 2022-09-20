<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TrainingStatistic extends Model
{
    use SoftDeletes;

    public $table = 'training_statistics';

    protected $dates = ['deleted_at'];

    public $fillable = [
        'recipient_id',
        'company_id',
        'code'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'recipient_id' => 'integer',
        'company_id' => 'integer',
        'code' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'recipient_id' => 'required',
        'company_id' => 'required',
        'code' => 'required'
    ];

    public function recipient()
    {
        return $this->belongsTo('App\Models\Recipient');
    }

    public function company()
    {
        return $this->belongsTo('App\Models\Company');
    }

}
