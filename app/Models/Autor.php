<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Autor extends Model
{
    /** @use HasFactory<\Database\Factories\AutorFactory> */
    use HasFactory;

    protected $table = 'autor';

    protected $primaryKey = 'cod_autor';

    public $timestamps = false;

    protected $fillable = [
        'nome',
    ];

    public function livros(): BelongsToMany
    {
        return $this->belongsToMany(
            Livro::class,
            'livro_autor',
            'autor_id',
            'livro_id',
            'cod_autor',
            'cod_livro'
        );
    }
}
