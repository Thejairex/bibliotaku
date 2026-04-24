<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UserProfileController extends Controller
{
    /**
     * Update the authenticated user's profile information (name, email, avatar).
     * Optionally also changes the password if provided.
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'avatar' => ['nullable', 'url', 'max:2048'],
        ];

        $passwordProvided = filled($request->input('password'));

        if ($passwordProvided) {
            $rules['current_password'] = ['required', 'string', 'current_password'];
            $rules['password'] = ['required', Password::defaults(), 'confirmed'];
            $rules['password_confirmation'] = ['required'];
        }

        $validated = $request->validate($rules);

        // Update email verification if email changed
        if ($user->email !== $validated['email']) {
            $user->email_verified_at = null;
        }

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->avatar = $validated['avatar'] ?? null;

        if ($passwordProvided) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()->route('profile')
            ->with('success', __('Profile updated successfully!'));
    }
}
