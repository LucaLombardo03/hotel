<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\RoomType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class BookingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function create(Request $request)
    {
        $roomType = RoomType::findOrFail($request->room_type_id);
        return view('bookings.create', compact('roomType'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'room_type_id' => 'required|exists:room_types,id',
            'check_in' => 'required|date|after_or_equal:today',
            'check_out' => 'required|date|after:check_in',
            'num_guests' => 'required|integer|min:1',
            'num_rooms' => 'required|integer|min:1',
            'notes' => 'nullable|string',
        ]);

        $roomType = RoomType::findOrFail($validated['room_type_id']);

        // Verifica disponibilità
        $available = $roomType->availableRooms($validated['check_in'], $validated['check_out']);

        if ($available < $validated['num_rooms']) {
            return back()->withErrors(['num_rooms' => 'Non ci sono abbastanza camere disponibili per le date selezionate.']);
        }

        // Calcola prezzo totale
        $checkIn = Carbon::parse($validated['check_in']);
        $checkOut = Carbon::parse($validated['check_out']);
        $nights = $checkIn->diffInDays($checkOut);
        $totalPrice = $roomType->price_per_night * $nights * $validated['num_rooms'];

        Booking::create([
            'user_id' => Auth::id(),
            'room_type_id' => $validated['room_type_id'],
            'check_in' => $validated['check_in'],
            'check_out' => $validated['check_out'],
            'num_guests' => $validated['num_guests'],
            'num_rooms' => $validated['num_rooms'],
            'total_price' => $totalPrice,
            'status' => 'pending',
            'notes' => $validated['notes'],
        ]);

        return redirect()->route('profile')->with('success', 'Prenotazione effettuata con successo!');
    }

    public function cancel($id)
    {
        $booking = Booking::findOrFail($id);

        if ($booking->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403);
        }

        $booking->update(['status' => 'cancelled']);

        return back()->with('success', 'Prenotazione cancellata.');
    }
}
