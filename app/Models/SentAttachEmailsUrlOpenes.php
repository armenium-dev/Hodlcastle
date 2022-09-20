<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Log;
use \App\Models\SentAttachEmails;

/**
 * @property int $id
 * @property int $sent_attach_email_id
 * @property string $hash
 * @property string $url
 * @property int $opened
 * @property string $created_at
 * @property string $updated_at
 */
class SentAttachEmailsUrlOpenes extends Model
{

	protected $table = 'sent_attach_emails_url_openes';

    /**
     * @var array
     */
    protected $fillable = ['sent_attach_email_id', 'hash', 'url', 'opens', 'created_at', 'updated_at'];

	public function email()
	{
		return $this->belongsTo(SentAttachEmails::class, 'sent_attach_email_id');
	}

}
