<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/', [App\Http\Controllers\Dash\DashboardController::class, 'index'])->name('dashboard.index');
    
    // Office
    Route::prefix('office')->group(function () {
        Route::get('json', [App\Http\Controllers\Dash\OfficeController::class, 'json'])->name('office.json');
    });
    Route::resource('office', App\Http\Controllers\Dash\OfficeController::class);

    // Shift
    Route::prefix('shift')->group(function () {
        Route::get('json', [App\Http\Controllers\Dash\ShiftController::class, 'json'])->name('shift.json');
    });
    Route::resource('shift', App\Http\Controllers\Dash\ShiftController::class);

    // Employee
    Route::prefix('employee')->group(function () {
        Route::get('json', [App\Http\Controllers\Dash\EmployeeController::class, 'json'])->name('employee.json');
        Route::get('template', [App\Http\Controllers\Dash\EmployeeController::class, 'template'])->name('employee.template');
        Route::post('import', [App\Http\Controllers\Dash\EmployeeController::class, 'import'])->name('employee.import');
    });
    Route::resource('employee', App\Http\Controllers\Dash\EmployeeController::class);

    // Leave
    Route::prefix('leave')->group(function () {
        Route::get('json', [App\Http\Controllers\Dash\LeaveController::class, 'json'])->name('leave.json');
        Route::get('request', [App\Http\Controllers\Dash\LeaveController::class, 'request'])->name('leave.request');
    });
    Route::resource('leave', App\Http\Controllers\Dash\LeaveController::class)->except(['edit', 'destroy']);

    // Schedule
    Route::prefix('schedule')->group(function () {
        Route::get('json', [App\Http\Controllers\Dash\ScheduleController::class, 'json'])->name('schedule.json');
        Route::get('regular', [App\Http\Controllers\Dash\ScheduleController::class, 'regular'])->name('regular');
        Route::get('regular/create', [App\Http\Controllers\Dash\ScheduleController::class, 'regularCreate'])->name('regular.create');
        Route::post('regular', [App\Http\Controllers\Dash\ScheduleController::class, 'regularStore'])->name('regular.store');
        Route::get('regular/{regular}/edit', [App\Http\Controllers\Dash\ScheduleController::class, 'regularEdit'])->name('regular.edit');
        Route::put('regular/{regular}', [App\Http\Controllers\Dash\ScheduleController::class, 'regularUpdate'])->name('regular.update');
        
        Route::get('shift', [App\Http\Controllers\Dash\ScheduleController::class, 'shift'])->name('sift');
    });
    // Route::resource('schedule', App\Http\Controllers\Dash\ScheduleController::class)->except(['edit', 'destroy']);

    // Attendance
    Route::prefix('attendance')->group(function () {
        // Route::get('json', [App\Http\Controllers\Dash\OfficeController::class, 'json'])->name('attendance.json');
    });
    Route::resource('attendance', App\Http\Controllers\Dash\AttendanceController::class);

    // Report
    Route::prefix('report')->group(function () {
        Route::get('/', [App\Http\Controllers\Dash\ReportController::class, 'index'])->name('report.index');
    });
});

Route::get('/login', [App\Http\Controllers\Auth\LoginController::class, 'index'])->name('login');
Route::post('/login', [App\Http\Controllers\Auth\LoginController::class, 'login']);
Route::get('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');