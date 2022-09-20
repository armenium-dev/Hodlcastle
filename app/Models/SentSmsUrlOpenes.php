<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Log;
use \App\Models\SentSms;

/**
 * @property int $id
 * @property int $sent_sms_id
 * @property string $hash
 * @property string $url
 * @property int $opened
 * @property string $created_at
 * @property string $updated_at
 */
class SentSmsUrlOpenes extends Model
{

	protected $table = 'sent_sms_url_openes';

    /**
     * @var array
     */
    protected $fillable = ['sent_sms_id', 'hash', 'url', 'opens', 'created_at', 'updated_at'];

	public function sms()
	{
		return $this->belongsTo(SentSms::class, 'sent_sms_id');
	}

}
