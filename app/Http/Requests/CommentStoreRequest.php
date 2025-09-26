<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CommentStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        // La autorización real se manejará en el controlador con Auth::id()
        return true;
    }

    public function rules(): array
    {
        return [
            'rating' => ['required', 'integer', 'between:1,5'],
            'content' => ['required', 'string', 'max:500'],
        ];
    }
}
