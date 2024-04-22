<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Traits\HasPermission;
use Illuminate\Database\Eloquent\SoftDeletes;


class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasPermission, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'first_name',
        'last_name',
        'email',
        'password',
        'code',
        'start_date',
        'official_start_date',
        'dependent_person',
        'gender',
        'contract',
        'birthday',
        'phone',
        'status',
        'position',
        'user_id',
        'avatar',
        'role_id',
        'team_id',
        'leave_hours_left',
        'official_employment_date',
        'resignation_date',
        'leave_update_date'

    ];
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function teams()
    {
        return $this->belongsToMany(Team::class);
    }

    public function remotes()
    {
        return $this->hasMany(Remote::class);
    }
    public function leaves()
    {
        return $this->hasMany(Leave::class);
    }
}
