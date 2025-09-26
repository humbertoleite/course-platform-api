<?php

namespace App\Http\Controllers\Api;

use App\Models\Instructor;
use App\Http\Requests\InstructorStoreRequest;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;

class InstructorController extends Controller
{
    public function index(): JsonResponse
    {
        $instructors = Instructor::all();

        return response()->json($instructors);
    }

    public function store(InstructorStoreRequest $request): JsonResponse
    {
        $instructor = Instructor::create($request->validated());

        return response()->json($instructor, 201); // 201 Created
    }
}
