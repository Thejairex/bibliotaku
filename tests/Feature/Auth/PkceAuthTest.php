<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

uses(RefreshDatabase::class);

beforeEach(function () {
    DB::table('oauth_clients')->insert([
        'id' => (string) Str::uuid(),
        'name' => 'Personal Access Client',
        'secret' => bcrypt('secret'),
        'redirect_uris' => '["http://localhost"]',
        'grant_types' => '["personal_access"]',
        'revoked' => false,
        'created_at' => now(),
        'updated_at' => now(),
    ]);
});

function createCodeChallenge(string $codeVerifier): string
{
    return rtrim(strtr(base64_encode(hash('sha256', $codeVerifier, true)), '+/', '-_'), '=');
}

function createPublicClient(): object
{
    $id = (string) Str::uuid();

    DB::table('oauth_clients')->insert([
        'id' => $id,
        'name' => 'Bibliotaku Mobile App',
        'secret' => null,
        'redirect_uris' => '["bibliotaku://callback"]',
        'grant_types' => '["authorization_code"]',
        'revoked' => false,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    return (object) ['id' => $id];
}

function obtainTokens(User $user, object $client): array
{
    $codeVerifier = Str::random(64);
    $codeChallenge = createCodeChallenge($codeVerifier);

    $authResponse = test()->postJson('/api/v1/auth/authorize', [
        'email' => $user->email,
        'password' => 'secret123',
        'client_id' => $client->id,
        'code_challenge' => $codeChallenge,
        'code_challenge_method' => 'S256',
        'state' => 'state',
    ]);

    $code = $authResponse->json('code');

    $tokenResponse = test()->postJson('/api/v1/auth/token', [
        'grant_type' => 'authorization_code',
        'client_id' => $client->id,
        'code' => $code,
        'code_verifier' => $codeVerifier,
        'redirect_uri' => 'bibliotaku://callback',
    ]);

    return [
        'access_token' => $tokenResponse->json('access_token'),
        'refresh_token' => $tokenResponse->json('refresh_token'),
    ];
}

test('authorize returns code for valid credentials and public client', function () {
    $user = User::factory()->create(['password' => 'secret123']);
    $client = createPublicClient();
    $codeVerifier = Str::random(64);
    $codeChallenge = createCodeChallenge($codeVerifier);

    $response = $this->postJson('/api/v1/auth/authorize', [
        'email' => $user->email,
        'password' => 'secret123',
        'client_id' => $client->id,
        'code_challenge' => $codeChallenge,
        'code_challenge_method' => 'S256',
        'state' => 'random-state-value',
    ]);

    $response->assertStatus(200)
        ->assertJsonStructure(['code', 'state', 'expires_in'])
        ->assertJson([
            'state' => 'random-state-value',
            'expires_in' => 600,
        ]);

    $code = $response->json('code');
    expect($code)->toBeString()->toHaveLength(80);

    $this->assertDatabaseHas('oauth_auth_codes', [
        'id' => $code,
        'user_id' => $user->id,
        'code_challenge' => $codeChallenge,
        'code_challenge_method' => 'S256',
        'revoked' => false,
    ]);
});

test('authorize fails with invalid credentials', function () {
    $user = User::factory()->create(['password' => 'secret123']);
    $client = createPublicClient();
    $codeVerifier = Str::random(64);
    $codeChallenge = createCodeChallenge($codeVerifier);

    $response = $this->postJson('/api/v1/auth/authorize', [
        'email' => $user->email,
        'password' => 'wrong-password',
        'client_id' => $client->id,
        'code_challenge' => $codeChallenge,
        'code_challenge_method' => 'S256',
        'state' => 'random-state-value',
    ]);

    $response->assertStatus(401)
        ->assertJson(['error' => 'invalid_grant']);
});

test('authorize fails with invalid client', function () {
    $user = User::factory()->create(['password' => 'secret123']);
    $codeVerifier = Str::random(64);
    $codeChallenge = createCodeChallenge($codeVerifier);

    $response = $this->postJson('/api/v1/auth/authorize', [
        'email' => $user->email,
        'password' => 'secret123',
        'client_id' => (string) Str::uuid(),
        'code_challenge' => $codeChallenge,
        'code_challenge_method' => 'S256',
        'state' => 'random-state-value',
    ]);

    $response->assertStatus(400)
        ->assertJson(['error' => 'invalid_client']);
});

test('token endpoint returns access_token and refresh_token', function () {
    $user = User::factory()->create(['password' => 'secret123']);
    $client = createPublicClient();
    $codeVerifier = Str::random(64);
    $codeChallenge = createCodeChallenge($codeVerifier);

    $authResponse = $this->postJson('/api/v1/auth/authorize', [
        'email' => $user->email,
        'password' => 'secret123',
        'client_id' => $client->id,
        'code_challenge' => $codeChallenge,
        'code_challenge_method' => 'S256',
        'state' => 'random-state-value',
    ]);

    $code = $authResponse->json('code');

    $tokenResponse = $this->postJson('/api/v1/auth/token', [
        'grant_type' => 'authorization_code',
        'client_id' => $client->id,
        'code' => $code,
        'code_verifier' => $codeVerifier,
        'redirect_uri' => 'bibliotaku://callback',
    ]);

    $tokenResponse->assertStatus(200)
        ->assertJsonStructure(['token_type', 'expires_in', 'access_token', 'refresh_token'])
        ->assertJson(['token_type' => 'Bearer']);

    $accessToken = $tokenResponse->json('access_token');
    $refreshToken = $tokenResponse->json('refresh_token');

    expect($accessToken)->toBeString()->not->toBeEmpty();
    expect($refreshToken)->toBeString()->not->toBeEmpty();

    $this->assertDatabaseHas('oauth_refresh_tokens', [
        'id' => $refreshToken,
        'revoked' => false,
    ]);

    $this->assertDatabaseHas('oauth_auth_codes', [
        'id' => $code,
        'revoked' => true,
    ]);
});

test('token endpoint fails with invalid code verifier', function () {
    $user = User::factory()->create(['password' => 'secret123']);
    $client = createPublicClient();
    $codeVerifier = Str::random(64);
    $codeChallenge = createCodeChallenge($codeVerifier);

    $authResponse = $this->postJson('/api/v1/auth/authorize', [
        'email' => $user->email,
        'password' => 'secret123',
        'client_id' => $client->id,
        'code_challenge' => $codeChallenge,
        'code_challenge_method' => 'S256',
        'state' => 'random-state-value',
    ]);

    $code = $authResponse->json('code');

    $tokenResponse = $this->postJson('/api/v1/auth/token', [
        'grant_type' => 'authorization_code',
        'client_id' => $client->id,
        'code' => $code,
        'code_verifier' => Str::random(64),
        'redirect_uri' => 'bibliotaku://callback',
    ]);

    $tokenResponse->assertStatus(400);
});

test('token endpoint fails with reused code', function () {
    $user = User::factory()->create(['password' => 'secret123']);
    $client = createPublicClient();
    $codeVerifier = Str::random(64);
    $codeChallenge = createCodeChallenge($codeVerifier);

    $authResponse = $this->postJson('/api/v1/auth/authorize', [
        'email' => $user->email,
        'password' => 'secret123',
        'client_id' => $client->id,
        'code_challenge' => $codeChallenge,
        'code_challenge_method' => 'S256',
        'state' => 'random-state-value',
    ]);

    $code = $authResponse->json('code');

    $payload = [
        'grant_type' => 'authorization_code',
        'client_id' => $client->id,
        'code' => $code,
        'code_verifier' => $codeVerifier,
        'redirect_uri' => 'bibliotaku://callback',
    ];

    $this->postJson('/api/v1/auth/token', $payload)->assertStatus(200);

    $this->postJson('/api/v1/auth/token', $payload)->assertStatus(400);
});

test('full pkce flow allows accessing protected api', function () {
    $user = User::factory()->create(['password' => 'secret123']);
    $client = createPublicClient();
    $tokens = obtainTokens($user, $client);

    $response = $this->getJson('/api/v1/user', [
        'Authorization' => 'Bearer '.$tokens['access_token'],
    ]);

    $response->assertStatus(200)
        ->assertJson(['email' => $user->email]);
});

test('refresh token returns new access_token and refresh_token', function () {
    $user = User::factory()->create(['password' => 'secret123']);
    $client = createPublicClient();
    $tokens = obtainTokens($user, $client);
    $oldRefreshToken = $tokens['refresh_token'];
    $oldAccessTokenId = DB::table('oauth_refresh_tokens')
        ->where('id', $oldRefreshToken)
        ->value('access_token_id');

    $response = $this->postJson('/api/v1/auth/refresh', [
        'grant_type' => 'refresh_token',
        'client_id' => $client->id,
        'refresh_token' => $oldRefreshToken,
    ]);

    $response->assertStatus(200)
        ->assertJsonStructure(['token_type', 'expires_in', 'access_token', 'refresh_token'])
        ->assertJson(['token_type' => 'Bearer']);

    expect($response->json('access_token'))->not->toBe($tokens['access_token']);
    expect($response->json('refresh_token'))->not->toBe($oldRefreshToken);

    $this->assertDatabaseHas('oauth_refresh_tokens', [
        'id' => $oldRefreshToken,
        'revoked' => true,
    ]);

    $this->assertDatabaseHas('oauth_refresh_tokens', [
        'id' => $response->json('refresh_token'),
        'revoked' => false,
    ]);

    $this->assertDatabaseHas('oauth_access_tokens', [
        'id' => $oldAccessTokenId,
        'revoked' => true,
    ]);
});

test('refresh token fails with reused refresh token', function () {
    $user = User::factory()->create(['password' => 'secret123']);
    $client = createPublicClient();
    $tokens = obtainTokens($user, $client);

    $payload = [
        'grant_type' => 'refresh_token',
        'client_id' => $client->id,
        'refresh_token' => $tokens['refresh_token'],
    ];

    $this->postJson('/api/v1/auth/refresh', $payload)->assertStatus(200);

    $this->postJson('/api/v1/auth/refresh', $payload)->assertStatus(400);
});

test('refreshed token can access protected api', function () {
    $user = User::factory()->create(['password' => 'secret123']);
    $client = createPublicClient();
    $tokens = obtainTokens($user, $client);

    $refreshResponse = $this->postJson('/api/v1/auth/refresh', [
        'grant_type' => 'refresh_token',
        'client_id' => $client->id,
        'refresh_token' => $tokens['refresh_token'],
    ]);

    $newAccessToken = $refreshResponse->json('access_token');

    $response = $this->getJson('/api/v1/user', [
        'Authorization' => 'Bearer '.$newAccessToken,
    ]);

    $response->assertStatus(200)
        ->assertJson(['email' => $user->email]);
});

test('refresh token fails with invalid refresh token', function () {
    $user = User::factory()->create(['password' => 'secret123']);
    $client = createPublicClient();

    $response = $this->postJson('/api/v1/auth/refresh', [
        'grant_type' => 'refresh_token',
        'client_id' => $client->id,
        'refresh_token' => Str::random(80),
    ]);

    $response->assertStatus(400)
        ->assertJson(['error' => 'invalid_grant']);
});
