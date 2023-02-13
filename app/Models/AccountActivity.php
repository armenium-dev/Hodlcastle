<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class AccountActivity extends Model
{
    protected $fillable = [
        'user_id',
        'action',
        'ip_address',
        'time'
    ];

    protected $table = 'account_activities';

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
