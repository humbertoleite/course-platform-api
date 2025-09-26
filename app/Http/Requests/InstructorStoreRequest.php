<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InstructorStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Adjust based on your Auth system (e.g., must be an Admin)
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:instructors,email'],
            'bio' => ['nullable', 'string'],
        ];
    }
}
