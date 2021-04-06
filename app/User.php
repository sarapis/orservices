<?php

namespace App;

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
        'email',
        'phone_number',
        'password',
        'last_name',
        'first_name',
        'permissions',
        'remember_token',
        'message',
        'user_organization',
        'role_id',
        'created_by',
        'status',
    ];
    public function roles()
    {
        return $this->belongsTo('App\Model\Role', 'role_id', 'id');
    }
    public function interations()
    {
        return $this->hasMany('App\Model\SessionData', 'session_performed_by', 'id');
    }
    public function edits()
    {
        return $this->hasMany('OwenIt\Auditing\Models\Audit', 'user_id', 'id');
    }
    public function organizations()
    {
        return $this->belongsToMany('App\Model\Organization', 'organization_users', 'user_id', 'organization_recordid');
    }
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
}
