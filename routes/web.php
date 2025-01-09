<?php

use App\Http\Controllers\Dash\CutiController;
use App\Http\Controllers\Dash\HomeController;
use App\Http\Controllers\Dash\KantorController;
use App\Http\Controllers\Dash\KaryawanController;
use App\Http\Controllers\Dash\LaporanController;
use App\Http\Controllers\Dash\PresensiController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/karyawan', [KaryawanController::class, 'index'])->name('karyawan');
Route::get('/cuti', [CutiController::class, 'index'])->name('cuti');
Route::get('/presensi', [PresensiController::class, 'index'])->name('presensi');
Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan');
Route::get('/kantor', [KantorController::class, 'index'])->name('kantor');