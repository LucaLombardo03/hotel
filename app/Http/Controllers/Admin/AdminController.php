<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    middleware(['auth', 'admin']);
    }

    public function dashboard()
    {
        $hotel = Hotel::with(['roomTypes', 'images'])->first();
        $bookings = Booking::with(['user', 'roomType'])->latest()->paginate(20);
        
        return view('admin.dashboard', compact('hotel', 'bookings'));
    }

    // GESTIONE HOTEL
    public function updateHotel(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'address' => 'required|string',
            'phone' => 'required|string',
            'email' => 'required|email',
            'stars' => 'required|integer|min:1|max:5',
        ]);

        $hotel = Hotel::first();
        
        if ($hotel) {
            $hotel->update($validated);
        } else {
            Hotel::create($validated);
        }

        return back()->with('success', 'Hotel aggiornato con successo!');
    }

}
