<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RelatorioLivroPorAutor extends Model
{
    protected $table = 'vw_relatorio_livros_por_autor';

    public $timestamps = false;

    public $incrementing = false;

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'cod_autor' => 'integer',
            'cod_livro' => 'integer',
            'edicao' => 'integer',
            'ano_publicacao' => 'integer',
            'valor' => 'decimal:2',
        ];
    }
}
