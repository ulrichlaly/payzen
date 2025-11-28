<?php

namespace App\Models;

use Laravel\Passport\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'fullname',
        'email',
        'password',
        'tel',
        'role_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];


    // public function role()
    // {
    //     return $this->belongsTo(Role::class);
    // }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_user');
    }


    public function declarations()
    {
        return $this->hasMany(Declaration::class);
    }

    public function collaborator()
    {
        return $this->hasOne(Collaborator::class);
    }
}
