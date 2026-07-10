<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Assunto;
use App\Models\Autor;
use App\Models\Livro;
use Illuminate\Database\Seeder;

class LivroSeeder extends Seeder
{
    public function run(): void
    {
        $autores = Autor::query()->get();
        $assuntos = Assunto::query()->get();

        $domCasmurro = Livro::factory()->create([
            'titulo' => 'Dom Casmurro',
            'editora' => 'Garnier',
            'edicao' => 1,
            'ano_publicacao' => 1899,
            'valor' => 49.90,
        ]);
        $domCasmurro->autores()->attach(
            $autores->where('nome', 'Machado de Assis')->pluck('cod_autor')
        );
        $domCasmurro->assuntos()->attach(
            $assuntos->whereIn('descricao', ['Romance', 'Ficção'])->pluck('cod_assunto')
        );

        $horaEstrela = Livro::factory()->create([
            'titulo' => 'A Hora da Estrela',
            'editora' => 'Rocco',
            'edicao' => 2,
            'ano_publicacao' => 1977,
            'valor' => 39.90,
        ]);
        $horaEstrela->autores()->attach(
            $autores->where('nome', 'Clarice Lispector')->pluck('cod_autor')
        );
        $horaEstrela->assuntos()->attach(
            $assuntos->whereIn('descricao', ['Romance', 'Filosofia'])->pluck('cod_assunto')
        );

        $antologia = Livro::factory()->create([
            'titulo' => 'Antologia da Literatura Brasileira',
            'editora' => 'Ática',
            'edicao' => 3,
            'ano_publicacao' => 2005,
            'valor' => 89.50,
        ]);
        $antologia->autores()->attach(
            $autores->whereIn('nome', ['Machado de Assis', 'Clarice Lispector', 'Cecília Meireles'])
                ->pluck('cod_autor')
        );
        $antologia->assuntos()->attach(
            $assuntos->whereIn('descricao', ['Poesia', 'Romance', 'História'])->pluck('cod_assunto')
        );

        Livro::factory()
            ->count(5)
            ->create()
            ->each(function (Livro $livro) use ($autores, $assuntos): void {
                $livro->autores()->attach(
                    $autores->random(fake()->numberBetween(1, 2))->pluck('cod_autor')
                );
                $livro->assuntos()->attach(
                    $assuntos->random(fake()->numberBetween(1, 3))->pluck('cod_assunto')
                );
            });
    }
}
