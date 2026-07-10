<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Author;
use Illuminate\Database\Seeder;

class AuthorSeeder extends Seeder
{
    public function run(): void
    {
        Author::factory()->create(['nome' => 'Machado de Assis']);
        Author::factory()->create(['nome' => 'Clarice Lispector']);
        Author::factory()->create(['nome' => 'José de Alencar']);
        Author::factory()->create(['nome' => 'Cecília Meireles']);
        Author::factory()->count(4)->create();
    }
}
