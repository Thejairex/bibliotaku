<?php

use App\Enums\MediaStatus;
use App\Enums\MediaType;
use App\Models\MediaEntry;
use App\Models\User;
use Illuminate\Http\UploadedFile;

function backupPayload(array $manga = [], array $categories = []): array
{
    return [
        'backupManga' => $manga,
        'backupCategories' => $categories,
    ];
}

function mangaFixture(array $overrides = []): array
{
    return array_merge([
        'source' => 'src-1',
        'url' => '/manga/1',
        'title' => 'Solo Leveling',
        'author' => 'Chugong',
        'description' => 'A weak hunter becomes strong.',
        'genre' => ['Action'],
        'status' => 2,
        'thumbnailUrl' => 'https://example.com/cover.jpg',
        'chapters' => [],
    ], $overrides);
}

it('rejects guests on the import page', function () {
    $this->get('/my-list/import')->assertRedirect('/login');
});

it('parses a valid backup and infers status from chapters', function () {
    $user = User::factory()->create(['email_verified_at' => now()]);

    $payload = backupPayload(
        manga: [
            mangaFixture([
                'title' => 'Fully Read',
                'chapters' => [
                    ['url' => '/c1', 'name' => 'C1', 'dateFetch' => 0, 'dateUpload' => 0, 'chapterNumber' => 1, 'version' => '0', 'read' => true],
                    ['url' => '/c2', 'name' => 'C2', 'dateFetch' => 0, 'dateUpload' => 0, 'chapterNumber' => 2, 'version' => '0', 'read' => true],
                ],
                'categories' => ['1'],
            ]),
            mangaFixture([
                'title' => 'Partially Read',
                'chapters' => [
                    ['url' => '/c1', 'name' => 'C1', 'dateFetch' => 0, 'dateUpload' => 0, 'chapterNumber' => 1, 'version' => '0', 'read' => true],
                    ['url' => '/c2', 'name' => 'C2', 'dateFetch' => 0, 'dateUpload' => 0, 'chapterNumber' => 2, 'version' => '0'],
                ],
                'categories' => ['1'],
            ]),
            mangaFixture([
                'title' => 'Untouched',
                'chapters' => [
                    ['url' => '/c1', 'name' => 'C1', 'dateFetch' => 0, 'dateUpload' => 0, 'chapterNumber' => 5, 'version' => '0'],
                ],
                'categories' => ['2'],
            ]),
        ],
        categories: [
            ['name' => 'manhwa', 'id' => '1', 'flags' => '0', 'order' => '0'],
            ['name' => 'pending', 'id' => '2', 'flags' => '0', 'order' => '1'],
        ],
    );

    $file = UploadedFile::fake()->createWithContent('backup.json', json_encode($payload));

    $response = $this->actingAs($user)
        ->post('/my-list/import/parse', ['file' => $file]);

    $response->assertOk();
    $data = $response->json();

    expect($data['stats']['total'])->toBe(3);
    expect($data['categories'])->toHaveCount(2);
    expect($data['entries'][0]['inferred_status'])->toBe(MediaStatus::Completed->value);
    expect($data['entries'][1]['inferred_status'])->toBe(MediaStatus::Reading->value);
    expect($data['entries'][2]['inferred_status'])->toBe(MediaStatus::PlanToWatch->value);
    expect($data['entries'][1]['current_chapter'])->toBe(1);
    expect($data['entries'][2]['total_chapters'])->toBe(5);
});

it('rejects malformed JSON files', function () {
    $user = User::factory()->create(['email_verified_at' => now()]);

    $file = UploadedFile::fake()->createWithContent('broken.json', '{not valid json');

    $this->actingAs($user)
        ->post('/my-list/import/parse', ['file' => $file])
        ->assertStatus(422);
});

it('creates new entries on commit', function () {
    $user = User::factory()->create(['email_verified_at' => now()]);

    $payload = [
        'fallback_type' => MediaType::Manhwa->value,
        'mapping' => [],
        'entries' => [
            [
                'index' => 0,
                'title' => 'Solo Leveling',
                'original_title' => null,
                'cover_url' => 'https://example.com/c.jpg',
                'total_chapters' => 200,
                'current_chapter' => 50,
                'inferred_status' => MediaStatus::Reading->value,
                'notes' => 'desc',
                'category_ids' => [],
            ],
        ],
    ];

    $this->actingAs($user)
        ->post('/my-list/import', $payload)
        ->assertRedirect(route('my-list'));

    expect(MediaEntry::where('user_id', $user->id)->count())->toBe(1);
    $entry = MediaEntry::where('user_id', $user->id)->first();
    expect($entry->type)->toBe(MediaType::Manhwa);
    expect($entry->current_chapter)->toBe(50);
});

it('updates existing entry when imported chapter is higher', function () {
    $user = User::factory()->create(['email_verified_at' => now()]);
    MediaEntry::factory()->for($user)->create([
        'title' => 'Solo Leveling',
        'type' => MediaType::Manhwa,
        'status' => MediaStatus::Reading,
        'current_chapter' => 30,
        'total_chapters' => 100,
    ]);

    $this->actingAs($user)->post('/my-list/import', [
        'fallback_type' => MediaType::Manhwa->value,
        'entries' => [[
            'index' => 0,
            'title' => 'solo leveling',
            'cover_url' => null,
            'total_chapters' => 200,
            'current_chapter' => 80,
            'inferred_status' => MediaStatus::Reading->value,
            'notes' => null,
            'category_ids' => [],
        ]],
    ])->assertRedirect();

    $entry = MediaEntry::where('user_id', $user->id)->first();
    expect($entry->current_chapter)->toBe(80);
    expect($entry->total_chapters)->toBe(200);
});

it('skips entries when existing chapter is greater or equal', function () {
    $user = User::factory()->create(['email_verified_at' => now()]);
    MediaEntry::factory()->for($user)->create([
        'title' => 'Solo Leveling',
        'type' => MediaType::Manhwa,
        'status' => MediaStatus::Reading,
        'current_chapter' => 100,
    ]);

    $this->actingAs($user)->post('/my-list/import', [
        'fallback_type' => MediaType::Manhwa->value,
        'entries' => [[
            'index' => 0,
            'title' => 'Solo Leveling',
            'cover_url' => null,
            'total_chapters' => 200,
            'current_chapter' => 50,
            'inferred_status' => MediaStatus::Reading->value,
            'notes' => null,
            'category_ids' => [],
        ]],
    ])->assertRedirect();

    $entry = MediaEntry::where('user_id', $user->id)->first();
    expect($entry->current_chapter)->toBe(100);
});

it('requires authentication to commit', function () {
    $this->post('/my-list/import', [
        'fallback_type' => MediaType::Manga->value,
        'entries' => [],
    ])->assertRedirect('/login');
});
