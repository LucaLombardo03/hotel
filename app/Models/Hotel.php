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

    public function roomTypes()
    {
        return $this->hasMany(RoomType::class);
    }

    public function images()
    {
        return $this->hasMany(HotelImage::class);
    }

    public function mainImage()
    {
        return $this->hasOne(HotelImage::class)->where('is_main', true);
    }
}
