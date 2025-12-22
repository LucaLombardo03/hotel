<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Booking extends Model
{
    protected $fillable = [
        'user_id',
        'room_type_id',
        'check_in',
        'check_out',
        'num_guests',
        'num_rooms',
        'total_price',
        'status',
        'notes'
    ];

    protected $casts = [
        'check_in' => 'date',
        'check_out' => 'date',
        'total_price' => 'decimal:2',
    ];

    // RELAZIONE CON UTENTE
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // RELAZIONE CON TIPOLOGIA CAMERA
    public function roomType()
    {
        return $this->belongsTo(RoomType::class);
    }

    // METODO PER CALCOLARE IL NUMERO DI NOTTI
    public function getNights()
    {
        return $this->check_in->diffInDays($this->check_out);
    }
}
