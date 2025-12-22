<?php
// FILE: routes/web.php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\AdminController;

// Home pubblica
Route::get('/', [HomeController::class, 'index'])->name('home');

// Autenticazione
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Prenotazioni (solo utenti loggati) - MIDDLEWARE QUI
Route::middleware(['auth'])->group(function () {
    Route::get('/booking/create', [BookingController::class, 'create'])->name('booking.create');
    Route::post('/booking', [BookingController::class, 'store'])->name('booking.store');
    Route::post('/booking/{id}/cancel', [BookingController::class, 'cancel'])->name('booking.cancel');
});

// Profilo utente - MIDDLEWARE QUI
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

// Admin - MIDDLEWARE QUI
Route::prefix('admin')->middleware(['auth', 'admin'])->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // Hotel
    Route::post('/hotel', [AdminController::class, 'updateHotel'])->name('hotel.update');

    // Immagini
    Route::post('/images', [AdminController::class, 'uploadImage'])->name('images.upload');
    Route::delete('/images/{id}', [AdminController::class, 'deleteImage'])->name('images.delete');

    // Room Types
    Route::post('/room-types', [AdminController::class, 'storeRoomType'])->name('room-types.store');
    Route::put('/room-types/{id}', [AdminController::class, 'updateRoomType'])->name('room-types.update');
    Route::delete('/room-types/{id}', [AdminController::class, 'deleteRoomType'])->name('room-types.delete');

    // Prenotazioni
    Route::put('/bookings/{id}/status', [AdminController::class, 'updateBookingStatus'])->name('bookings.update-status');
});
