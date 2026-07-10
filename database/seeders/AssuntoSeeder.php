<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Assunto;
use Illuminate\Database\Seeder;

class AssuntoSeeder extends Seeder
{
    public function run(): void
    {
        Assunto::factory()->create(['descricao' => 'Romance']);
        Assunto::factory()->create(['descricao' => 'Poesia']);
        Assunto::factory()->create(['descricao' => 'Ficção']);
        Assunto::factory()->create(['descricao' => 'História']);
        Assunto::factory()->create(['descricao' => 'Filosofia']);
        Assunto::factory()->count(3)->create();
    }
}
