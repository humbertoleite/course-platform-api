<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class UserRegistrationRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Registration is a public action
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => [
                'required',
                'confirmed', // Requires a 'password_confirmation' field
                Password::defaults(), // Laravel's default complexity rules
            ],
        ];
    }
}
