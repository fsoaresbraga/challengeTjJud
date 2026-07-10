<?php

declare(strict_types=1);

namespace Tests\Feature\Api;

use App\Models\Author;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthorApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_basic_author_crud(): void
    {
        $create = $this->postJson('/api/authors', ['name' => 'Machado de Assis']);
        $create->assertCreated()->assertJsonPath('data.name', 'Machado de Assis');
        $id = $create->json('data.authorId');

        $this->getJson('/api/authors')->assertOk()->assertJsonFragment(['name' => 'Machado de Assis']);
        $this->getJson("/api/authors/{$id}")->assertOk()->assertJsonPath('data.name', 'Machado de Assis');

        $this->putJson("/api/authors/{$id}", ['name' => 'Machado'])
            ->assertOk()
            ->assertJsonPath('data.name', 'Machado');

        $this->deleteJson("/api/authors/{$id}")->assertNoContent();
        $this->assertDatabaseMissing('autor', ['cod_autor' => $id]);
    }

    public function test_missing_author_returns_404(): void
    {
        $this->getJson('/api/authors/9999')
            ->assertNotFound()
            ->assertJsonPath('code', 'AUTHOR_NOT_FOUND');
    }

    public function test_name_is_required(): void
    {
        $this->postJson('/api/authors', [])
            ->assertUnprocessable()
            ->assertJsonPath('code', 'VALIDATION_ERROR');
    }

    public function test_can_list_authors(): void
    {
        Author::factory()->count(2)->create();

        $this->getJson('/api/authors')
            ->assertOk()
            ->assertJsonCount(2, 'data');
    }
}
