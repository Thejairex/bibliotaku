<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Laravel\Passport\Passport;

class AuthorizeController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
            'client_id' => 'required|uuid',
            'code_challenge' => 'required|string|min:43|max:128',
            'code_challenge_method' => 'required|in:S256',
            'state' => 'required|string|min:1|max:255',
        ]);

        if (! Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'error' => 'invalid_grant',
                'message' => 'The provided credentials are incorrect.',
            ], 401);
        }

        $client = Passport::client()
            ->where('id', $request->client_id)
            ->whereNull('secret')
            ->where('revoked', false)
            ->first();

        if (! $client) {
            return response()->json([
                'error' => 'invalid_client',
                'message' => 'Client not found, not a public client, or revoked.',
            ], 400);
        }

        $code = Str::random(80);

        DB::table('oauth_auth_codes')->insert([
            'id' => $code,
            'user_id' => Auth::id(),
            'client_id' => $client->id,
            'scopes' => '[]',
            'revoked' => false,
            'expires_at' => now()->addMinutes(10),
            'code_challenge' => $request->code_challenge,
            'code_challenge_method' => $request->code_challenge_method,
            'redirect_uri' => $request->input('redirect_uri', 'bibliotaku://callback'),
        ]);

        return response()->json([
            'code' => $code,
            'state' => $request->state,
            'expires_in' => 600,
        ]);
    }
}
