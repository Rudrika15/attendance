<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserApiController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AttendanceController;

// use App\Http\Controllers\Api\AttendanceController;
Route::post('/auth/register', [AuthController::class, 'createUser']);
Route::post('/auth/login', [AuthController::class, 'loginUser']);
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/show-attendance', [AttendanceController::class ,'index']);
    Route::get('/today-attendance', [AttendanceController::class ,'todayattendance']);
    Route::post('/attendance', [AttendanceController::class ,'store']);
    Route::get('/attendance/delete', [AttendanceController::class ,'deleteAttendance']);
});



