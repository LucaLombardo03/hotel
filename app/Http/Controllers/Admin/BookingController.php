<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index()
    {
        $bookings = Booking::with(['user', 'roomType'])
            ->where('status', '!=', 'cancelled')
            ->latest()
            ->paginate(10);

        return view('admin.bookings.index', compact('bookings'));
    }

    /**
     * Aggiorna lo stato della prenotazione (Confermata, Pagata, ecc.)
     */
    public function updateStatus(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);
        
        $request->validate([
            'status' => 'required|in:pending,confirmed,checked_in,checked_out,cancelled'
        ]);

        $booking->status = $request->status;
        $booking->save();

        return back()->with('success', 'Stato prenotazione aggiornato con successo.');
    }
}