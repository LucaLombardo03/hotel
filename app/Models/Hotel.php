<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hotel extends Model
{
    protected $fillable = [
        'name',
        'description',
        'address',
        'phone',
        'email',
        'stars',
        'amenities'
    ];

    protected $casts = [
        'amenities' => 'array',
    ];

    // RELAZIONE CON TIPOLOGIE CAMERE
    public function roomTypes()
    {
        return $this->hasMany(RoomType::class);
    }

    // RELAZIONE CON IMMAGINI
    public function images()
    {
        return $this->hasMany(HotelImage::class);
    }

    // IMMAGINE PRINCIPALE
    public function mainImage()
    {
        return $this->hasOne(HotelImage::class)->where('is_main', true);
    }
}
