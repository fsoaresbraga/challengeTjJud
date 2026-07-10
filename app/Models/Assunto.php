<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Assunto extends Model
{
    /** @use HasFactory<\Database\Factories\AssuntoFactory> */
    use HasFactory;

    protected $table = 'assunto';

    protected $primaryKey = 'cod_assunto';

    public $timestamps = false;

    protected $fillable = [
        'descricao',
    ];

    public function livros(): BelongsToMany
    {
        return $this->belongsToMany(
            Livro::class,
            'livro_assunto',
            'assunto_id',
            'livro_id',
            'cod_assunto',
            'cod_livro'
        );
    }
}
