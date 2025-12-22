<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use App\Models\RoomType;
use App\Models\HotelImage;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
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

    // GESTIONE IMMAGINI
    public function uploadImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_main' => 'boolean',
        ]);

        $hotel = Hotel::first();

        if (!$hotel) {
            return back()->withErrors(['error' => 'Crea prima l\'hotel']);
        }

        $path = $request->file('image')->store('hotel_images', 'public');

        if ($request->is_main) {
            HotelImage::where('hotel_id', $hotel->id)->update(['is_main' => false]);
        }

        HotelImage::create([
            'hotel_id' => $hotel->id,
            'image_path' => $path,
            'is_main' => $request->is_main ?? false,
        ]);

        return back()->with('success', 'Immagine caricata!');
    }

    public function deleteImage($id)
    {
        $image = HotelImage::findOrFail($id);
        Storage::disk('public')->delete($image->image_path);
        $image->delete();

        return back()->with('success', 'Immagine eliminata!');
    }

    // GESTIONE ROOM TYPES
    public function storeRoomType(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price_per_night' => 'required|numeric|min:0',
            'max_guests' => 'required|integer|min:1',
            'total_rooms' => 'required|integer|min:1',
        ]);

        $hotel = Hotel::first();

        if (!$hotel) {
            return back()->withErrors(['error' => 'Crea prima l\'hotel']);
        }

        RoomType::create([
            'hotel_id' => $hotel->id,
            'name' => $validated['name'],
            'description' => $validated['description'],
            'price_per_night' => $validated['price_per_night'],
            'max_guests' => $validated['max_guests'],
            'total_rooms' => $validated['total_rooms'],
        ]);

        return back()->with('success', 'Tipologia camera aggiunta!');
    }

    public function updateRoomType(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price_per_night' => 'required|numeric|min:0',
            'max_guests' => 'required|integer|min:1',
            'total_rooms' => 'required|integer|min:1',
        ]);

        $roomType = RoomType::findOrFail($id);
        $roomType->update($validated);

        return back()->with('success', 'Tipologia camera aggiornata!');
    }

    public function deleteRoomType($id)
    {
        $roomType = RoomType::findOrFail($id);
        $roomType->delete();

        return back()->with('success', 'Tipologia camera eliminata!');
    }

    // GESTIONE PRENOTAZIONI
    public function updateBookingStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,cancelled',
        ]);

        $booking = Booking::findOrFail($id);
        $booking->update(['status' => $validated['status']]);

        return back()->with('success', 'Stato prenotazione aggiornato!');
    }
}
