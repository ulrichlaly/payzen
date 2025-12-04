<?php

namespace App\Models;

use Laravel\Passport\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

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

    // ✅ SUPPRIMEZ CES MÉTHODES - Le trait Notifiable les fournit déjà !
    // public function notifications() { ... }
    // public function unreadNotifications() { ... }

    /**
     * ✅ Vérifier si l'utilisateur est admin
     */
    public function isAdmin(): bool
    {
        return $this->roles()->whereIn('name', ['Admin', 'Administrateur'])->exists();
    }

    /**
     * ✅ Vérifier si l'utilisateur a un rôle spécifique
     */
    public function hasRole(string|array $roles): bool
    {
        if (is_string($roles)) {
            $roles = [$roles];
        }
        return $this->roles()->whereIn('name', $roles)->exists();
    }
}
