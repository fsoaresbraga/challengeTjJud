<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Subject;
use Illuminate\Database\Seeder;

class SubjectSeeder extends Seeder
{
    public function run(): void
    {
        Subject::factory()->create(['descricao' => 'Romance']);
        Subject::factory()->create(['descricao' => 'Poesia']);
        Subject::factory()->create(['descricao' => 'Ficção']);
        Subject::factory()->create(['descricao' => 'História']);
        Subject::factory()->create(['descricao' => 'Filosofia']);
        Subject::factory()->count(3)->create();
    }
}
