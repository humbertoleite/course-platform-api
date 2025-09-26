<?php

namespace App\Http\Controllers\Api;

use App\Models\Instructor;
use App\Http\Requests\InstructorStoreRequest;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
class InstructorController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json($this->getOptimizedInstructors());
    }
    protected function getOptimizedInstructors()
    {
        // Cache for 60 minutes (3600 seconds) to reduce DB load for millions of records
        return Cache::remember('all_instructors_list', 3600, function () {
            return Instructor::select('id', 'name','bio')
                             ->orderBy('name')
                             ->get();
        });
    }
    public function store(InstructorStoreRequest $request): JsonResponse
    {
        $instructor = Instructor::create($request->validated());

        return response()->json($instructor, 201); // 201 Created
    }
}
