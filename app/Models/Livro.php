<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Livro extends Model
{
    /** @use HasFactory<\Database\Factories\LivroFactory> */
    use HasFactory;

    protected $table = 'livro';

    protected $primaryKey = 'cod_livro';

    public $timestamps = false;

    protected $fillable = [
        'titulo',
        'editora',
        'edicao',
        'ano_publicacao',
        'valor',
    ];

    protected function casts(): array
    {
        return [
            'edicao' => 'integer',
            'ano_publicacao' => 'integer',
            'valor' => 'decimal:2',
        ];
    }

    public function autores(): BelongsToMany
    {
        return $this->belongsToMany(
            Autor::class,
            'livro_autor',
            'livro_id',
            'autor_id',
            'cod_livro',
            'cod_autor'
        );
    }

    public function assuntos(): BelongsToMany
    {
        return $this->belongsToMany(
            Assunto::class,
            'livro_assunto',
            'livro_id',
            'assunto_id',
            'cod_livro',
            'cod_assunto'
        );
    }
}
