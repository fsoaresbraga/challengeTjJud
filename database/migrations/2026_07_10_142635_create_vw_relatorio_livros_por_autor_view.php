<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('DROP VIEW IF EXISTS vw_relatorio_livros_por_autor');

        DB::statement("
            CREATE VIEW vw_relatorio_livros_por_autor AS
            SELECT
                a.cod_autor,
                a.nome AS autor_nome,
                l.cod_livro,
                l.titulo,
                l.editora,
                l.edicao,
                l.ano_publicacao,
                l.valor,
                assuntos_agrupados.assuntos
            FROM livro l
            INNER JOIN livro_autor la ON la.livro_id = l.cod_livro
            INNER JOIN autor a ON a.cod_autor = la.autor_id
            LEFT JOIN (
                SELECT la2.livro_id, GROUP_CONCAT(s.descricao ORDER BY s.descricao SEPARATOR ', ') AS assuntos
                FROM livro_assunto la2
                INNER JOIN assunto s ON s.cod_assunto = la2.assunto_id
                GROUP BY la2.livro_id
            ) AS assuntos_agrupados ON assuntos_agrupados.livro_id = l.cod_livro
            ORDER BY a.nome, l.titulo
        ");
    }

    public function down(): void
    {
        DB::statement('DROP VIEW IF EXISTS vw_relatorio_livros_por_autor');
    }
};
