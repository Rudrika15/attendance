<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('auth/login');
});
// Route::get('/', function () {
//     return view('auth/login');
// });

Auth::routes();


Route::group(['middleware' => ['auth']], function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/approve/leave/{id}', [HomeController::class, 'leaveApproved'])->name('leave.approve');
    Route::resource('roles', RoleController::class);

    Route::resource('users', UserController::class);

    Route::get('/Report', [ReportController::class, 'display'])->name('report.display');
    Route::get('/notification', [HomeController::class, 'notification'])->name('notification.index');
    Route::post('/notification', [HomeController::class, 'addNotification'])->name('add.notification');
    Route::get('/notification/delete/{id}', [HomeController::class, 'deleteNotification'])->name('delete.notification');
    Route::get('/leave/report', [LeaveController::class, 'index'])->name('leave.report');
});

Route::get('happy-birthday', [UserController::class, 'wish']);
Route::get('privacy-policy', [UserController::class, 'privacyPolicy']);
