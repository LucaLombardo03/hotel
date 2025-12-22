<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use App\Models\HotelImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HotelController extends Controller
{
    // Aggiorna info generali Hotel
    public function update(Request $request)
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

    // Carica immagine
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

    // Elimina immagine
    public function deleteImage($id)
    {
        $image = HotelImage::findOrFail($id);

        // Elimina il file fisico
        if (Storage::disk('public')->exists($image->image_path)) {
            Storage::disk('public')->delete($image->image_path);
        }

        $image->delete();

        return back()->with('success', 'Immagine eliminata!');
    }
}
