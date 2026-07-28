<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Laravel\Passport\Passport;

class RefreshTokenController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $request->validate([
            'grant_type' => 'required|in:refresh_token',
            'client_id' => 'required|uuid',
            'refresh_token' => 'required|string',
        ]);

        $client = Passport::client()->find($request->client_id);

        if (! $client || $client->revoked) {
            return response()->json([
                'error' => 'invalid_client',
                'message' => 'Client not found or revoked.',
            ], 400);
        }

        $refreshToken = DB::table('oauth_refresh_tokens')
            ->where('id', $request->refresh_token)
            ->where('revoked', false)
            ->first();

        if (! $refreshToken || now()->gt($refreshToken->expires_at)) {
            return response()->json([
                'error' => 'invalid_grant',
                'message' => 'Refresh token is invalid or expired.',
            ], 400);
        }

        $accessToken = DB::table('oauth_access_tokens')
            ->where('id', $refreshToken->access_token_id)
            ->first();

        if (! $accessToken) {
            return response()->json([
                'error' => 'invalid_grant',
                'message' => 'Access token not found.',
            ], 400);
        }

        $user = User::find($accessToken->user_id);

        if (! $user) {
            return response()->json([
                'error' => 'invalid_grant',
                'message' => 'User not found.',
            ], 400);
        }

        DB::table('oauth_access_tokens')
            ->where('id', $refreshToken->access_token_id)
            ->update(['revoked' => true]);

        DB::table('oauth_refresh_tokens')
            ->where('id', $request->refresh_token)
            ->update(['revoked' => true]);

        $tokenResult = $user->createToken($client->name);

        $newRefreshTokenId = Str::random(80);

        DB::table('oauth_refresh_tokens')->insert([
            'id' => $newRefreshTokenId,
            'access_token_id' => $tokenResult->accessTokenId,
            'revoked' => false,
            'expires_at' => now()->addDays(30),
        ]);

        return response()->json([
            'token_type' => 'Bearer',
            'expires_in' => (int) ($tokenResult->expiresIn ?? 1296000),
            'access_token' => $tokenResult->accessToken,
            'refresh_token' => $newRefreshTokenId,
        ]);
    }
}
