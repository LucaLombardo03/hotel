<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    /**
     * Mostra il profilo dell'utente e la lista delle prenotazioni attive.
     * Le prenotazioni con stato 'cancelled' vengono ignorate.
     */
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // MODIFICA: Aggiunto filtro per nascondere le prenotazioni cancellate dalla vista
        $bookings = $user->bookings()
            ->where('status', '!=', 'cancelled')
            ->with('roomType')
            ->latest()
            ->get();

        return view('profile.index', compact('user', 'bookings'));
    }

    /**
     * Aggiorna i dati anagrafici e la password dell'utente.
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->phone = $validated['phone'] ?? $user->phone;
        $user->address = $validated['address'] ?? $user->address;

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return back()->with('success', 'Profilo aggiornato con successo!');
    }
}