<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class AccountActivity extends Model
{
    protected $fillable = [
        'user_id',
        'company_id',
        'campaign_id',
        'action',
        'ip_address',
        'sms_credit',
    ];

    protected $table = 'account_activities';

    const ACTION_LOGIN = 'Login';
    const ACTION_SMS_CREDIT = 'SMS Credit';

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
