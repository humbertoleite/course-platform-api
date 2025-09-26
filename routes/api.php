<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\Api\InstructorController;
use App\Http\Controllers\Api\AuthController;
use \App\Http\Controllers\Api\CourseController;
use \App\Http\Controllers\Api\CommentController;
use \App\Http\Controllers\Api\LessonController;

Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);

Route::prefix('v1')->middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::apiResource('instructors', InstructorController::class)->only(['store', 'index']);
    Route::post('courses/{course}/comments', [CommentController::class, 'store']);
    Route::apiResource('courses/{course}/lessons', LessonController::class);
    Route::apiResource('courses', CourseController::class);
    Route::get('instructors/list', [CourseController::class, 'getInstructorsList']);
    Route::post('courses/{course}/favorite', [CourseController::class, 'toggleFavorite']);
    Route::get('favorites', [CourseController::class, 'indexFavorites']);

});
