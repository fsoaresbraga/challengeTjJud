<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('DROP VIEW IF EXISTS vw_relatorio_livros_por_autor');

        $groupConcat = DB::getDriverName() === 'sqlite'
            ? "GROUP_CONCAT(s.descricao, ', ')"
            : "GROUP_CONCAT(s.descricao ORDER BY s.descricao SEPARATOR ', ')";

        $quote = static fn (string $alias): string => DB::getDriverName() === 'sqlite'
            ? "\"{$alias}\""
            : "`{$alias}`";

        DB::statement("
            CREATE VIEW vw_relatorio_livros_por_autor AS
            SELECT
                a.cod_autor AS {$quote('authorId')},
                a.nome AS {$quote('authorName')},
                l.cod_livro AS {$quote('bookId')},
                l.titulo AS {$quote('title')},
                l.editora AS {$quote('publisher')},
                l.edicao AS {$quote('edition')},
                l.ano_publicacao AS {$quote('publicationYear')},
                l.valor AS {$quote('price')},
                assuntos_agrupados.assuntos AS {$quote('subjects')}
            FROM livro l
            INNER JOIN livro_autor la ON la.livro_id = l.cod_livro
            INNER JOIN autor a ON a.cod_autor = la.autor_id
            LEFT JOIN (
                SELECT la2.livro_id, {$groupConcat} AS assuntos
                FROM livro_assunto la2
                INNER JOIN assunto s ON s.cod_assunto = la2.assunto_id
                GROUP BY la2.livro_id
            ) AS assuntos_agrupados ON assuntos_agrupados.livro_id = l.cod_livro
        ");
    }

    public function down(): void
    {
        DB::statement('DROP VIEW IF EXISTS vw_relatorio_livros_por_autor');
    }
};
