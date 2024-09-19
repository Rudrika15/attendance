<?php

use App\Http\Controllers\Api\AdminController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserApiController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AttendanceController;
use App\Http\Controllers\Api\TaskController;

// use App\Http\Controllers\Api\AttendanceController;
Route::post('/auth/register', [AuthController::class, 'createUser']);
Route::post('/auth/login', [AuthController::class, 'loginUser']);
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/show-attendance', [AttendanceController::class, 'index']);
    Route::get('/today-attendance', [AttendanceController::class, 'todayattendance']);
    Route::post('/attendance', [AttendanceController::class, 'store']);
    Route::get('/attendance/delete', [AttendanceController::class, 'deleteAttendance']);


    // change password
    Route::post('/auth/change-password', [AuthController::class, 'changePassword']);
    Route::post('/leave/request', [AttendanceController::class, 'leaveRequest']);
    Route::get('/leaves', [AttendanceController::class, 'myLeaves']);
    Route::post('/save-token', [AuthController::class, 'saveToken']);


    // admin routes
    Route::get('/daily', [AdminController::class, 'dailyAttendance']);
    Route::get('/leave-applications', [AdminController::class, 'leaveApplication']);
    Route::post('/leave-approval/{id}', [AdminController::class, 'approveLeave']);
    Route::post('/leave/cancel/{id}', [AdminController::class, 'cancelLeave']);
    
    Route::get('/notifications', [AttendanceController::class, 'getNotifications']);
    //task
    Route::get('/mytask',[TaskController::class,'myTask']);
    Route::post('/task',[TaskController::class,'addTask']);
    Route::delete('/deleteTask',[TaskController::class,'deleteTask']);
    Route::get('/showTask',[TaskController::class,'showTask']);
    Route::post('/updateTask',[TaskController::class,'updateTask']);
    Route::get('/allTask',[TaskController::class,'allTask']);

});
