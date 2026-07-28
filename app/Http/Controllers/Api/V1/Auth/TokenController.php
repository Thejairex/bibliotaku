<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Laravel\Passport\Passport;

class TokenController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $request->validate([
            'grant_type' => 'required|in:authorization_code',
            'client_id' => 'required|uuid',
            'code' => 'required|string',
            'code_verifier' => 'required|string|min:43|max:128',
            'redirect_uri' => 'required|string',
        ]);

        $authCode = DB::table('oauth_auth_codes')
            ->where('id', $request->code)
            ->where('revoked', false)
            ->first();

        if (! $authCode || now()->gt($authCode->expires_at)) {
            return response()->json([
                'error' => 'invalid_grant',
                'message' => 'Authorization code is invalid or expired.',
            ], 400);
        }

        if ($authCode->client_id !== $request->client_id) {
            return response()->json([
                'error' => 'invalid_grant',
                'message' => 'Client mismatch.',
            ], 400);
        }

        $client = Passport::client()->find($request->client_id);

        if (! $client || $client->revoked) {
            return response()->json([
                'error' => 'invalid_client',
                'message' => 'Client not found or revoked.',
            ], 400);
        }

        $this->validatePkce($authCode, $request->code_verifier);

        DB::table('oauth_auth_codes')
            ->where('id', $request->code)
            ->update(['revoked' => true]);

        $user = User::find($authCode->user_id);

        if (! $user) {
            return response()->json([
                'error' => 'invalid_grant',
                'message' => 'User not found.',
            ], 400);
        }

        $tokenResult = $user->createToken($client->name);

        return response()->json([
            'token_type' => 'Bearer',
            'expires_in' => (int) ($tokenResult->expiresIn ?? 1296000),
            'access_token' => $tokenResult->accessToken,
            'refresh_token' => null,
        ]);
    }

    private function validatePkce(object $authCode, string $codeVerifier): void
    {
        if (preg_match('/^[A-Za-z0-9\-._~]{43,128}$/', $codeVerifier) !== 1) {
            abort(400, 'Code verifier format is invalid.');
        }

        $codeChallengeMethod = $authCode->code_challenge_method ?? 'S256';

        if ($codeChallengeMethod === 'S256') {
            $computed = rtrim(strtr(base64_encode(hash('sha256', $codeVerifier, true)), '+/', '-_'), '=');

            if (! hash_equals($computed, $authCode->code_challenge)) {
                abort(400, 'Invalid code_verifier.');
            }
        }
    }
}
