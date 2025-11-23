<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canRegister' => Features::enabled(Features::registration()),
    ]);
})->name('home');

// POST route for iOS app to send device UUID
Route::post('/', function () {
    // The DetectDeviceUuid middleware will handle the device_uuid
    // If user is authenticated (device found), middleware redirects to dashboard
    // If not authenticated, show appropriate auth page
    
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }
    
    // Check if device UUID was sent but not found in database
    if (session()->has('device_uuid')) {
        // Device UUID is new, redirect to register page
        return redirect()->route('register');
    }
    
    return redirect()->route('home');
})->name('home.post');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Quote route
Route::get('/quote/random', [App\Http\Controllers\QuoteController::class, 'show'])
    ->middleware(['auth'])
    ->name('quote.random');

// Device authentication routes
Route::post('/device/register', [App\Http\Controllers\DeviceAuthController::class, 'register'])
    ->name('device.register');

Route::post('/device/login', [App\Http\Controllers\DeviceAuthController::class, 'login'])
    ->name('device.login');

require __DIR__.'/settings.php';
