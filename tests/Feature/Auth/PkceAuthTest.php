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

test('token endpoint exchanges code for access token', function () {
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
        ->assertJsonStructure(['token_type', 'expires_in', 'access_token'])
        ->assertJson(['token_type' => 'Bearer']);

    $accessToken = $tokenResponse->json('access_token');
    expect($accessToken)->toBeString()->not->toBeEmpty();

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
    $accessToken = $tokenResponse->json('access_token');

    $response = $this->getJson('/api/v1/user', [
        'Authorization' => 'Bearer '.$accessToken,
    ]);

    $response->assertStatus(200)
        ->assertJson(['email' => $user->email]);
});
