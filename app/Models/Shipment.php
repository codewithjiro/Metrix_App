<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shipment extends Model
{
    use HasFactory;

    // Mass Assignment protection (Security)
    protected $fillable = [
        'user_id',
        'sender_name',
        'sender_address',
        'receiver_name',
        'receiver_address',
        'package_type',
        'price',
        'status',
    ];

    /**
     * Relationship back to the User (Sender)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}