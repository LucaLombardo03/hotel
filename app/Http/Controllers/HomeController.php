<?php

namespace App\Http\Controllers;

use App\Models\Hotel;
use Illuminate\Http\Request;
use App\Models\RoomType;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $hotel = Hotel::with(['images'])->first();

        $query = RoomType::query();

        if ($request->filled('guests')) {
            $query->where('max_guests', '>=', $request->guests);
        }

        if ($request->filled('max_price')) {
            $query->where('price_per_night', '<=', $request->max_price);
        }

        $roomTypes = $query->get();

        if (!$hotel) {
            return view('home', ['hotel' => null, 'roomTypes' => collect()]);
        }

        return view('home', compact('hotel', 'roomTypes'));
    }
}
