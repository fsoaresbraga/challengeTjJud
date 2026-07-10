<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Book extends Model
{
    /** @use HasFactory<\Database\Factories\BookFactory> */
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

    public function authors(): BelongsToMany
    {
        return $this->belongsToMany(
            Author::class,
            'livro_autor',
            'livro_id',
            'autor_id',
            'cod_livro',
            'cod_autor'
        );
    }

    public function subjects(): BelongsToMany
    {
        return $this->belongsToMany(
            Subject::class,
            'livro_assunto',
            'livro_id',
            'assunto_id',
            'cod_livro',
            'cod_assunto'
        );
    }
}
