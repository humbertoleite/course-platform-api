<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\Api\InstructorController;
use App\Http\Controllers\Api\AuthController;
use \App\Http\Controllers\Api\CourseController;
use \App\Http\Controllers\Api\CommentController;

Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);

Route::prefix('v1')->middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::apiResource('instructors', InstructorController::class)->only(['store', 'index']);
    Route::apiResource('courses', CourseController::class);
    Route::get('instructors/list', [CourseController::class, 'getInstructorsList']);
});
