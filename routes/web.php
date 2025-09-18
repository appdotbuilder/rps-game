<?php

use App\Http\Controllers\RockPaperScissorsController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/health-check', function () {
    return response()->json([
        'status' => 'ok',
        'timestamp' => now()->toISOString(),
    ]);
})->name('health-check');

// Rock Paper Scissors game routes
Route::controller(RockPaperScissorsController::class)->group(function () {
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
