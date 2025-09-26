<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Http\Requests\CommentStoreRequest; // Lo crearemos en el paso 3
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(CommentStoreRequest $request, Course $course): JsonResponse
    {
        if ($course->comments()->where('user_id', Auth::id())->exists()) {
            return response()->json([
                'message' => 'Ya has calificado y comentado este curso.'
            ], 409);
        }

        $comment = $course->comments()->create([
            'user_id' => Auth::id(),
            'rating' => $request->rating,
            'content' => $request->content,
        ]);



        return response()->json($comment->load('user'), 201);
    }
}
