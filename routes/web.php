<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/', [App\Http\Controllers\Dash\HomeController::class, 'index'])->name('home');
    Route::get('/employee', [App\Http\Controllers\Dash\EmployeeController::class, 'index'])->name('employee');
    Route::get('/leave', [App\Http\Controllers\Dash\LeaveController::class, 'index'])->name('leave');
    Route::get('/attendance', [App\Http\Controllers\Dash\AttendanceController::class, 'index'])->name('attendance');
    Route::get('/report', [App\Http\Controllers\Dash\ReportController::class, 'index'])->name('report');
    
    // Office
    Route::prefix('office')->group(function () {
        Route::get('json', [App\Http\Controllers\Dash\OfficeController::class, 'json'])->name('office.json');
    });
    Route::resource('office', App\Http\Controllers\Dash\OfficeController::class);
});

Route::get('/login', [App\Http\Controllers\Auth\LoginController::class, 'index'])->name('login');
Route::post('/login', [App\Http\Controllers\Auth\LoginController::class, 'login']);
Route::get('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');