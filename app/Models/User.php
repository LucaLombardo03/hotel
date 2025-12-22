<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'address'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // METODO PER VERIFICARE SE È ADMIN
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    // RELAZIONE CON PRENOTAZIONI
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
