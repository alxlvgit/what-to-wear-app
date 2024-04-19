<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/set-email-notification', [ProfileController::class, 'setEmailNotification'])->name('set-email-notification');
    Route::delete('/delete-email-notification', [ProfileController::class, 'deleteEmailNotification'])->name('delete-email-notification');
});

require __DIR__ . '/auth.php';
