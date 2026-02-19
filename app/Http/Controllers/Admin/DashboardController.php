<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use App\Models\Booking;

class DashboardController extends Controller
{
    
    public function index()
    {
        $hotel = Hotel::with(['roomTypes', 'images'])->first();
        
        // Filtriamo per escludere le prenotazioni cancellate dalla lista admin
        $bookings = Booking::with(['user', 'roomType'])
            ->where('status', '!=', 'cancelled')
            ->latest()
            ->paginate(20);

        return view('admin.dashboard', compact('hotel', 'bookings'));
    }
}