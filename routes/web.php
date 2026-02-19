<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ProfileController;

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\HotelController;
use App\Http\Controllers\Admin\RoomTypeController;
use App\Http\Controllers\Admin\BookingController as AdminBookingController; // Alias per non confondersi

// Home pubblica
Route::get('/', [HomeController::class, 'index'])->name('home');

// Autenticazione
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Prenotazioni (solo utenti loggati)
Route::middleware(['auth'])->group(function () {
    Route::get('/booking/create', [BookingController::class, 'create'])->name('booking.create');
    Route::post('/booking', [BookingController::class, 'store'])->name('booking.store');
    Route::post('/booking/{id}/cancel', [BookingController::class, 'cancel'])->name('booking.cancel');
});

// Profilo utente
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

// AREA AMMINISTRATORE
Route::prefix('admin')->middleware(['auth', 'admin'])->name('admin.')->group(function () {

    // Dashboard Principale
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Gestione Hotel & Immagini (HotelController)
    Route::post('/hotel', [HotelController::class, 'update'])->name('hotel.update');
    Route::post('/images', [HotelController::class, 'uploadImage'])->name('images.upload');
    Route::delete('/images/{id}', [HotelController::class, 'deleteImage'])->name('images.delete');

    // Gestione Room Types (RoomTypeController)
    Route::post('/room-types', [RoomTypeController::class, 'store'])->name('room-types.store');
    Route::put('/room-types/{id}', [RoomTypeController::class, 'update'])->name('room-types.update');
    Route::delete('/room-types/{id}', [RoomTypeController::class, 'destroy'])->name('room-types.delete');

    // Gestione Prenotazioni (AdminBookingController)
    Route::put('/bookings/{id}/status', [AdminBookingController::class, 'updateStatus'])->name('bookings.update-status');
});
