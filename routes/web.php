<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MediaEntryController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('profile', [\App\Http\Controllers\ProfileController::class, 'show'])->name('profile');
    Route::patch('profile/update', [\App\Http\Controllers\UserProfileController::class, 'update'])->name('profile.update');

    
    Route::resource('my-list', MediaEntryController::class)->names([
        'index' => 'my-list',
    ])->parameters([
        'my-list' => 'mediaEntry',
    ]);
});

require __DIR__.'/settings.php';
