<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Author extends Model
{
    /** @use HasFactory<\Database\Factories\AuthorFactory> */
    use HasFactory;

    protected $table = 'autor';

    protected $primaryKey = 'cod_autor';

    public $timestamps = false;

    protected $fillable = [
        'nome',
    ];

    public function books(): BelongsToMany
    {
        return $this->belongsToMany(
            Book::class,
            'livro_autor',
            'autor_id',
            'livro_id',
            'cod_autor',
            'cod_livro'
        );
    }
}
