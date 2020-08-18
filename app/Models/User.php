<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'user_type_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime', 
    ];

    public function details()
    {
        return $this->hasOne('App\Models\UserDetail', 'user_id', 'id');
    }

    public function adresses()
    {
        return $this->hasMany('App\Models\UserAdresse', 'user_id', 'id');
    }

    public function stockLog()
    {
        return $this->hasMany('App\Models\StockLog', 'user_id', 'id');
    }

    public function orders()
    {
        return $this->hasMany('App\Models\Orders', 'user_id', 'id');
    }

    public function user_type()
    {
        return $this->belongsTo('App\Models\UserType', 'user_id', 'id');
    }
}
