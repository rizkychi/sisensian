<?php

use Illuminate\Support\Facades\Route;

Route::get('/', [App\Http\Controllers\Dash\HomeController::class, 'index'])->name('home');
Route::get('/employee', [App\Http\Controllers\Dash\EmployeeController::class, 'index'])->name('employee');
Route::get('/leave', [App\Http\Controllers\Dash\LeaveController::class, 'index'])->name('leave');
Route::get('/attendance', [App\Http\Controllers\Dash\AttendanceController::class, 'index'])->name('attendance');
Route::get('/report', [App\Http\Controllers\Dash\ReportController::class, 'index'])->name('report');
Route::get('/office', [App\Http\Controllers\Dash\OfficeController::class, 'index'])->name('office');