<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function updateStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,cancelled',
        ]);

        $booking = Booking::findOrFail($id);
        $booking->update(['status' => $validated['status']]);

        return back()->with('success', 'Stato prenotazione aggiornato!');
    }
}
