<?php

use App\Http\Controllers\SettingsController;
use App\Http\Controllers\UserProfileController;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Route::get('settings/profile', [UserProfileController::class, 'edit'])->name('settings.profile');
    Route::patch('settings/profile', [UserProfileController::class, 'update'])->name('settings.profile.update');
    Route::delete('settings/profile', [UserProfileController::class, 'destroy'])->name('settings.profile.destroy');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('settings/appearance', [SettingsController::class, 'appearance'])->name('settings.appearance');
    Route::get('settings/security', [SettingsController::class, 'security'])
        ->middleware(
            when(
                Features::canManageTwoFactorAuthentication()
                && Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
                ['password.confirm'],
                [],
            ),
        )
        ->name('settings.security');
});
