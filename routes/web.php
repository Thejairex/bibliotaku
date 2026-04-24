<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MediaEntryController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\UserProfileController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('profile', [ProfileController::class, 'show'])->name('profile');
    Route::patch('profile/update', [UserProfileController::class, 'update'])->name('profile.update');
    Route::get('search', [SearchController::class, 'index'])->name('search');

    Route::resource('my-list', MediaEntryController::class)->names([
        'index' => 'my-list',
    ])->parameters([
        'my-list' => 'mediaEntry',
    ]);
});

require __DIR__.'/settings.php';
