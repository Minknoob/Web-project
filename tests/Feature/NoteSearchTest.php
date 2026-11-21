<?php

namespace Tests\Feature;

use App\Models\Note;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NoteSearchTest extends TestCase
{
    use RefreshDatabase;

    public function test_search_matches_title_or_content_for_the_authenticated_user(): void
    {
        $user = User::factory()->create();
        $titleMatch = $user->notes()->create([
            'title' => 'Trip planning',
            'content' => 'Dates and route',
        ]);
        $contentMatch = $user->notes()->create([
            'title' => 'Shopping list',
            'content' => 'Remember the trip documents',
        ]);
        $user->notes()->create([
            'title' => 'Weekly tasks',
            'content' => 'No matching text',
        ]);
        $otherUserNote = User::factory()->create()->notes()->create([
            'title' => 'Trip notes',
            'content' => 'Private note from another user',
        ]);

        $response = $this->actingAs($user)
            ->getJson('/notes?search=trip');

        $response->assertOk()
            ->assertJsonCount(2, 'notes')
            ->assertJsonFragment(['id' => $titleMatch->id])
            ->assertJsonFragment(['id' => $contentMatch->id])
            ->assertJsonMissing(['id' => $otherUserNote->id]);
    }

    public function test_search_query_is_validated(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->getJson('/notes?search=' . str_repeat('a', 256))
            ->assertUnprocessable()
            ->assertJsonValidationErrors('search');
    }
}
