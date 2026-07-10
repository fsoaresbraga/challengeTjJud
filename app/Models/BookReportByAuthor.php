<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookReportByAuthor extends Model
{
    protected $table = 'vw_relatorio_livros_por_autor';

    public $timestamps = false;

    public $incrementing = false;

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'authorId' => 'integer',
            'bookId' => 'integer',
            'edition' => 'integer',
            'publicationYear' => 'integer',
            'price' => 'decimal:2',
        ];
    }
}
