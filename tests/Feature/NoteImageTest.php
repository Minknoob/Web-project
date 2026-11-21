<?php

namespace Tests\Feature;

use App\Models\NoteImage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class NoteImageTest extends TestCase
{
    use RefreshDatabase;

    public function test_owner_can_upload_multiple_images_to_a_note(): void
    {
        Storage::fake('public');
        $user = User::factory()->create();
        $note = $user->notes()->create([
            'title' => 'Photo note',
            'content' => 'Images',
        ]);

        $response = $this->actingAs($user)->postJson('/notes/' . $note->id . '/images', [
            'images' => [
                $this->fakePng('first.png'),
                $this->fakePng('second.png'),
            ],
        ]);

        $response->assertCreated()->assertJsonCount(2, 'images');
        $this->assertDatabaseCount('note_images', 2);

        foreach (NoteImage::query()->where('note_id', $note->id)->get() as $image) {
            Storage::disk('public')->assertExists($image->path);
        }
    }

    public function test_note_image_upload_rejects_non_image_files(): void
    {
        Storage::fake('public');
        $user = User::factory()->create();
        $note = $user->notes()->create([
            'title' => 'Photo note',
            'content' => 'Images',
        ]);

        $this->actingAs($user)
            ->postJson('/notes/' . $note->id . '/images', [
                'images' => [UploadedFile::fake()->create('document.txt', 10, 'text/plain')],
            ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('images.0');
    }

    public function test_only_the_note_owner_can_upload_images(): void
    {
        Storage::fake('public');
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();
        $note = $owner->notes()->create([
            'title' => 'Private note',
            'content' => 'Content',
        ]);

        $this->actingAs($otherUser)
            ->postJson('/notes/' . $note->id . '/images', [
                'images' => [$this->fakePng('photo.png')],
            ])
            ->assertForbidden();

        $this->assertDatabaseCount('note_images', 0);
    }

    private function fakePng(string $name): UploadedFile
    {
        $contents = base64_decode(
            'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAIAAACQd1PeAAAADUlEQVR4nGP4z8AAAAMBAQDJ/pLvAAAAAElFTkSuQmCC'
        );

        return UploadedFile::fake()->createWithContent($name, $contents);
    }
}
