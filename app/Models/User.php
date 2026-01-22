<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Shipment; // Ensure this is singular now

class User extends Authenticatable
{
    // Removed HasApiTokens to fix the "Trait not found" error
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'role', 'profile_photo'
    ];

    public function isAdmin() {
        return $this->role === 1;
    }

    public function shipments() {
        // Ensure this is Shipment (singular)
        return $this->hasMany(Shipment::class);
    }
}