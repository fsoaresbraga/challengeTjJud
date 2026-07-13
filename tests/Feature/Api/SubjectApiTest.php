<?php

declare(strict_types=1);

namespace Tests\Feature\Api;

use App\Models\Subject;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SubjectApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_basic_subject_crud(): void
    {
        $create = $this->postJson('/api/subjects', ['description' => 'Romance']);
        $create->assertCreated()->assertJsonPath('data.description', 'Romance');
        $id = $create->json('data.subjectId');

        $this->getJson('/api/subjects')->assertOk()->assertJsonFragment(['description' => 'Romance']);
        $this->getJson("/api/subjects/{$id}")->assertOk()->assertJsonPath('data.description', 'Romance');

        $this->putJson("/api/subjects/{$id}", ['description' => 'Fiction'])
            ->assertOk()
            ->assertJsonPath('data.description', 'Fiction');

        $this->deleteJson("/api/subjects/{$id}")->assertNoContent();
        $this->assertDatabaseMissing('assunto', ['cod_assunto' => $id]);
    }

    public function test_missing_subject_returns_404(): void
    {
        $this->getJson('/api/subjects/9999')
            ->assertNotFound()
            ->assertJsonPath('code', 'SUBJECT_NOT_FOUND');
    }

    public function test_description_is_required(): void
    {
        $this->postJson('/api/subjects', [])
            ->assertUnprocessable()
            ->assertJsonPath('code', 'VALIDATION_ERROR');
    }

    public function test_description_max_length_is_20(): void
    {
        $this->postJson('/api/subjects', ['description' => str_repeat('a', 21)])
            ->assertUnprocessable()
            ->assertJsonPath('code', 'VALIDATION_ERROR')
            ->assertJsonValidationErrors(['description']);
    }

    public function test_can_list_subjects(): void
    {
        Subject::factory()->count(2)->create();

        $this->getJson('/api/subjects')
            ->assertOk()
            ->assertJsonCount(2, 'data');
    }
}
