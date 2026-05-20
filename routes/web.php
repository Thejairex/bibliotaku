<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MediaEntryController;
use App\Http\Controllers\MediaImportController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\UserProfileController;
use Illuminate\Support\Facades\Route;

Route::inertia('/', 'Home')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('profile', [ProfileController::class, 'show'])->name('profile');
    Route::patch('profile/update', [UserProfileController::class, 'update'])->name('profile.update');
    Route::get('search', [SearchController::class, 'index'])->name('search');
    Route::get('search/query', [SearchController::class, 'search'])->name('search.query');

    Route::get('my-list/import', [MediaImportController::class, 'create'])->name('my-list.import.create');
    Route::post('my-list/import/parse', [MediaImportController::class, 'parse'])->name('my-list.import.parse');
    Route::post('my-list/import', [MediaImportController::class, 'store'])->name('my-list.import.store');

    Route::resource('my-list', MediaEntryController::class)->names([
        'index' => 'my-list',
    ])->parameters([
        'my-list' => 'mediaEntry',
    ]);
});

require __DIR__.'/settings.php';
