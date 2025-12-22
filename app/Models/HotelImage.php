<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HotelImage extends Model
{
    protected $fillable = [
        'hotel_id',
        'image_path',
        'is_main'
    ];

    protected $casts = [
        'is_main' => 'boolean',
    ];

    // RELAZIONE CON HOTEL
    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }
}
