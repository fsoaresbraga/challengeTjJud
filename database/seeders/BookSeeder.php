<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Author;
use App\Models\Book;
use App\Models\Subject;
use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
{
    public function run(): void
    {
        $authors = Author::query()->get();
        $subjects = Subject::query()->get();

        $domCasmurro = Book::factory()->create([
            'titulo' => 'Dom Casmurro',
            'editora' => 'Garnier',
            'edicao' => 1,
            'ano_publicacao' => 1899,
            'valor' => 49.90,
        ]);
        $domCasmurro->authors()->attach(
            $authors->where('nome', 'Machado de Assis')->pluck('cod_autor')
        );
        $domCasmurro->subjects()->attach(
            $subjects->whereIn('descricao', ['Romance', 'Ficção'])->pluck('cod_assunto')
        );

        $horaEstrela = Book::factory()->create([
            'titulo' => 'A Hora da Estrela',
            'editora' => 'Rocco',
            'edicao' => 2,
            'ano_publicacao' => 1977,
            'valor' => 39.90,
        ]);
        $horaEstrela->authors()->attach(
            $authors->where('nome', 'Clarice Lispector')->pluck('cod_autor')
        );
        $horaEstrela->subjects()->attach(
            $subjects->whereIn('descricao', ['Romance', 'Filosofia'])->pluck('cod_assunto')
        );

        $anthology = Book::factory()->create([
            'titulo' => 'Antologia da Literatura Brasileira',
            'editora' => 'Ática',
            'edicao' => 3,
            'ano_publicacao' => 2005,
            'valor' => 89.50,
        ]);
        $anthology->authors()->attach(
            $authors->whereIn('nome', ['Machado de Assis', 'Clarice Lispector', 'Cecília Meireles'])
                ->pluck('cod_autor')
        );
        $anthology->subjects()->attach(
            $subjects->whereIn('descricao', ['Poesia', 'Romance', 'História'])->pluck('cod_assunto')
        );

        Book::factory()
            ->count(5)
            ->create()
            ->each(function (Book $book) use ($authors, $subjects): void {
                $book->authors()->attach(
                    $authors->random(fake()->numberBetween(1, 2))->pluck('cod_autor')
                );
                $book->subjects()->attach(
                    $subjects->random(fake()->numberBetween(1, 3))->pluck('cod_assunto')
                );
            });
    }
}
