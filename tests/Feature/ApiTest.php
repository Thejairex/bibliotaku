<?php

namespace Tests\Feature;

use App\Enums\MediaStatus;
use App\Enums\MediaType;
use App\Models\MediaEntry;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use Tests\TestCase;

class ApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_authenticate_with_passport()
    {
        $user = User::factory()->create();

        Passport::actingAs($user);

        $response = $this->getJson('/api/v1/user');

        $response->assertStatus(200)
            ->assertJson(['email' => $user->email]);
    }

    public function test_can_create_media_entry_via_api()
    {
        $user = User::factory()->create();
        Passport::actingAs($user);

        $response = $this->postJson('/api/v1/media-entries', [
            'title' => 'Test Anime',
            'type' => MediaType::Anime->value,
            'status' => MediaStatus::Watching->value,
            'current_episode' => 5,
        ]);

        $response->assertStatus(201)
            ->assertJsonPath('title', 'Test Anime');

        $this->assertDatabaseHas('media_entries', [
            'title' => 'Test Anime',
            'user_id' => $user->id,
        ]);
    }

    public function test_cannot_access_others_media_entries()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $mediaEntry = MediaEntry::factory()->create(['user_id' => $user2->id]);

        Passport::actingAs($user1);

        $response = $this->getJson("/api/v1/media-entries/{$mediaEntry->id}");

        $response->assertStatus(403);
    }
}
