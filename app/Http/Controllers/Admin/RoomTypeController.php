<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RoomType;
use App\Models\Hotel;
use Illuminate\Http\Request;

class RoomTypeController extends Controller
{
    public function store(Request $request)
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

    public function update(Request $request, $id)
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

    public function destroy($id)
    {
        $roomType = RoomType::findOrFail($id);
        $roomType->delete();

        return back()->with('success', 'Tipologia camera eliminata!');
    }
}
