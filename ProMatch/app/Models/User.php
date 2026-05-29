<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable, HasRoles;

    protected $fillable = [
        'first_name',
        'last_name', 
        'email',
        'password',
        'phone',
        'is_blocked',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'is_blocked' => 'boolean',
    ];

    protected $attributes = [
        'is_blocked' => false,
    ];

    public function owner() { return $this->hasOne(Owner::class); }
    public function tenant() { return $this->hasOne(Tenant::class); }
    public function employee() { return $this->hasOne(Employee::class); }
}
