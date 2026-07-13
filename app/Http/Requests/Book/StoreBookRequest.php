<?php

declare(strict_types=1);

namespace App\Http\Requests\Book;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreBookRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:40'],
            'publisher' => ['required', 'string', 'max:40'],
            'edition' => ['required', 'integer', 'min:1'],
            'publicationYear' => ['required', 'integer', 'min:1000', 'max:9999'],
            'price' => ['required', 'numeric', 'min:0'],
            'authors' => ['required', 'array', 'min:1'],
            'authors.*' => ['integer', 'distinct', Rule::exists('autor', 'cod_autor')],
            'subjects' => ['required', 'array', 'min:1'],
            'subjects.*' => ['integer', 'distinct', Rule::exists('assunto', 'cod_assunto')],
        ];
    }
}
