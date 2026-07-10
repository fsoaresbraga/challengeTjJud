<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Autor;
use Illuminate\Database\Seeder;

class AutorSeeder extends Seeder
{
    public function run(): void
    {
        Autor::factory()->create(['nome' => 'Machado de Assis']);
        Autor::factory()->create(['nome' => 'Clarice Lispector']);
        Autor::factory()->create(['nome' => 'José de Alencar']);
        Autor::factory()->create(['nome' => 'Cecília Meireles']);
        Autor::factory()->count(4)->create();
    }
}
