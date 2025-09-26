<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\UserRegistrationRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
class AuthController extends Controller
{
    public function login(Request $request): JsonResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            // Generate the token. The token name is descriptive.
            $token = $user->createToken('api-token')->plainTextToken;

            return response()->json([
                'token' => $token,
                'user' => $user->name,
                'message' => 'Login successful.'
            ]);
        }

        return response()->json([
            'message' => 'Invalid credentials.'
        ], 401); // 401 Unauthorized
    }

    public function register(UserRegistrationRequest $request): JsonResponse
    {
        // 1. Validation is handled by UserRegistrationRequest

        // 2. Create the User, hashing the password
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), // Always hash passwords!
        ]);

        // 3. Immediately generate an API token for the newly created user
        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user' => $user->name,
            'message' => 'Registration successful. Token generated.'
        ], 201); // 201 Created
    }
}
