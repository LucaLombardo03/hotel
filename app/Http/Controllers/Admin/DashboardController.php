<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use App\Models\Booking;

class DashboardController extends Controller
{
    public function index()
    {
        // Recupera i dati necessari per popolare la dashboard unica
        $hotel = Hotel::with(['roomTypes', 'images'])->first();
        $bookings = Booking::with(['user', 'roomType'])->latest()->paginate(20);

        return view('admin.dashboard', compact('hotel', 'bookings'));
    }
}
