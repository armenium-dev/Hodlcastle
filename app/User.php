<?php

namespace App;

use App\Models\AccountActivity;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use Carbon\Carbon;
use App\Notifications\ResetPassword;

class User extends Authenticatable
{
    use Notifiable;
    use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'company_id', 'is_active', 'send_to_landing', 'redirect_url',
    ];

    public static $rules = [
        'name' => 'required',
        'email' => 'required|email|unique:users,email',
//        'password' => 'required|min:6|confirmed',
        'password' => 'required|min:16|confirmed|regex:/((?=.*\d)(?=.*[0-9])(?=.*[a-zA-Z])(?=.*[@#$%!-]))/',
        'company_id' => 'required|numeric',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

//    public function setPasswordAttribute($password)
//    {
//        $this->attributes['password'] = bcrypt($password);
//    }

    public function beforeCreate()
    {
        $this->status = 1;
    }

    public function company()
    {
        return $this->belongsTo('App\Models\Company');
    }

    public function logo()
    {
        return $this->morphOne('App\Models\Image', 'imageable');
    }

    public function getActiveAttribute()
    {
        return $this->company && $this->company->active && $this->is_active == 1;
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPassword($token));
    }

    public function accountActivities()
    {
        return $this->hasMany(AccountActivity::class);
    }
}
