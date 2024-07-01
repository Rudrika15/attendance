<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Auth;

// Route::get('/', function () {
//     return view('auth/login');
// });
// Route::get('/', function () {
//     return view('auth/login');
// });

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('/approve/leave/{id}', [HomeController::class, 'leaveApproved'])->name('leave.approve');

Route::group(['middleware' => ['auth']], function () {
    Route::resource('roles', RoleController::class);

    Route::resource('users', UserController::class);

    Route::get('/Report', [ReportController::class, 'display'])->name('report.display');
});
