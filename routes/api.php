<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\Api\InstructorController;
use App\Http\Controllers\Api\AuthController;



Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);
