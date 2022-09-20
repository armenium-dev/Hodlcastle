<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Result
 * @package App\Models
 * @version July 25, 2018, 2:53 pm UTC
 *
 * @property integer campaign_id
 * @property integer customer_id
 * @property integer redirect_id
 * @property string email
 * @property string phone
 * @property string first_name
 * @property string last_name
 * @property integer status
 * @property string ip
 * @property string lat
 * @property string lng
 * @property timestamp send_date
 * @property integer reported
 * @property integer sent
 * @property integer open
 * @property integer click
 * @property integer attachment
 * @property integer fake_auth
 *
 * @property integer smish
 */
class Result extends Model
{
    use SoftDeletes;

    const TYPE_SENT = 1;
    const TYPE_OPEN = 2;
    const TYPE_CLICK = 3;
    const TYPE_REPORT = 4;
    const TYPE_ATTACH = 5;
    const TYPE_SMISH = 6;
    const TYPE_FAKE_AUTH = 7;

    public $table = 'results';

    protected $dates = ['deleted_at'];

    protected $appends = ['type'];

    public $fillable = [
        'campaign_id',
        'customer_id',
        'redirect_id',
        'email',
        'phone',
        'first_name',
        'last_name',
        'status',
        'ip',
        'lat',
        'lng',
        'send_date',
        'reported',
        'sent',
        'open',
        'click',
        'attachment',
        'fake_auth',
        'smish',
        'user_agent',
        'recipient_id',
        'type_id',
        'event_id',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'campaign_id' => 'integer',
        'customer_id' => 'integer',
        'redirect_id' => 'integer',
        'email' => 'string',
        'phone' => 'string',
        'first_name' => 'string',
        'last_name' => 'string',
        'status' => 'integer',
        'ip' => 'string',
        'lat' => 'string',
        'lng' => 'string',
        'reported' => 'integer',
        'sent' => 'integer',
        'open' => 'integer',
        'click' => 'integer',
        'attachment' => 'integer',
        'fake_auth' => 'integer',
        'smish' => 'integer',
        'user_agent' => 'string',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'campaign_id' => 'required|numeric',
        //'customer_id' => 'required|numeric',
        //'redirect_id' => 'required|numeric',
        'email' => 'required|email',
        'first_name' => 'required|alpha',
        'last_name' => 'required|alpha',
        'status' => 'required',
        //'ip' => 'text',
        //'lat' => 'text',
        //'lng' => 'required',
        'send_date' => 'required'
    ];

    public function campaign()
    {
        return $this->belongsTo('App\Models\Campaign');
    }

    public function recipient()
    {
        return $this->belongsTo('App\Models\Recipient');
    }

    public function scopeType($q, $type)
    {
        $type_id = self::getTypeIdByTitle($type);

        return $q->where('type_id', $type_id);
    }

    public static function sTypeTitles()
    {
        $out = [
            self::TYPE_SENT => 'sent',
            self::TYPE_OPEN => 'open',
            self::TYPE_CLICK => 'click',
            self::TYPE_ATTACH => 'attachment',
            self::TYPE_FAKE_AUTH => 'fake_auth',
            self::TYPE_SMISH => 'smish',
            self::TYPE_REPORT => 'report',
        ];

        return $out;
    }

    public static function getTypeIdByTitle($type)
    {
        $titles = self::sTypeTitles();
        $titles_flip = array_flip($titles);
        $type_id = $titles_flip[$type];

        return $type_id;
    }

    public function getTypeAttribute()
    {
        $titles = self::sTypeTitles();

        return $titles[$this->type_id] ?? null;
    }
}
