<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Laravel\Fortify\Features;

class SettingsController extends Controller
{
    /**
     * Show appearance settings.
     */
    public function appearance()
    {
        return Inertia::render('Settings/Appearance');
    }

    /**
     * Show security settings.
     */
    public function security()
    {
        return Inertia::render('Settings/Security', [
            'confirmsPassword' => Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
            'enabledTwoFactor' => ! is_null(auth()->user()->two_factor_secret),
        ]);
    }
}
