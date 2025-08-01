<?php

use App\Http\Controllers\GameController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/health-check', function () {
    return response()->json([
        'status' => 'ok',
        'timestamp' => now()->toISOString(),
    ]);
})->name('health-check');

// Paper-Rock-Scissors Game Routes
Route::controller(GameController::class)->group(function () {
    Route::get('/', 'index')->name('home');
    Route::post('/play', 'store')->name('game.play');
    Route::delete('/reset', 'destroy')->name('game.reset');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        return Inertia::render('dashboard');
    })->name('dashboard');
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
