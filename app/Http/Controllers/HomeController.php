<?php

namespace App\Http\Controllers;

use App\Models\Hotel;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $hotel = Hotel::with(['images', 'roomTypes'])->first();

        if (!$hotel) {
            return view('home')->with('hotel', null);
        }

        return view('home', compact('hotel'));
    }
}
