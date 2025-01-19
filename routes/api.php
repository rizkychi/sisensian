<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::prefix('auth')->group(function () {
    // Route::post('/login', [App\Http\Controllers\Auth\AuthController::class, 'login']);
    // Route::post('/register', 'AuthController@register');
    // Route::post('/logout', [App\Http\Controllers\Auth\AuthController::class, 'logout'])->middleware('auth:sanctum');
    // Route::post('/refresh', 'AuthController@refresh')->middleware('auth:sanctum');
    // Route::post('/user', [App\Http\Controllers\Auth\AuthController::class, 'user'])->middleware('auth:sanctum');

    Route::get('/employees/{office_id?}', [App\Http\Controllers\Dash\EmployeeController::class, 'getEmployeesByOffice'])->name('employee.get');
});