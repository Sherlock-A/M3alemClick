<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'phone', 'password',
        'role', 'status', 'rejection_reason',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    public function professional(): HasOne
    {
        return $this->hasOne(Professional::class);
    }

    public function isAdmin(): bool       { return $this->role === 'admin'; }
    public function isProfessional(): bool { return $this->role === 'professional'; }
    public function isClient(): bool       { return $this->role === 'client'; }

    public function isActive(): bool    { return $this->status === 'actif'; }
    public function isPending(): bool   { return $this->status === 'en_attente'; }
    public function isRefused(): bool   { return $this->status === 'refuse'; }
    public function isSuspended(): bool { return $this->status === 'suspendu'; }

    public function getStatusBadge(): array
    {
        return match($this->status) {
            'en_attente' => ['class' => 'bg-yellow-100 text-yellow-700', 'label' => 'En attente'],
            'actif'      => ['class' => 'bg-green-100 text-green-700',  'label' => 'Actif'],
            'refuse'     => ['class' => 'bg-red-100 text-red-700',      'label' => 'Refusé'],
            'suspendu'   => ['class' => 'bg-gray-100 text-gray-700',    'label' => 'Suspendu'],
            default      => ['class' => 'bg-gray-100 text-gray-700',    'label' => 'Inconnu'],
        };
    }
}
