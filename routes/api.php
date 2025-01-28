<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::prefix('auth')->group(function () {
    Route::post('/login', [App\Http\Controllers\Auth\AuthController::class, 'login']);
    // Route::post('/register', 'AuthController@register');
    // Route::post('/logout', [App\Http\Controllers\Auth\AuthController::class, 'logout'])->middleware('auth:sanctum');
    // Route::post('/refresh', 'AuthController@refresh')->middleware('auth:sanctum');
    // Route::post('/user', [App\Http\Controllers\Auth\AuthController::class, 'user'])->middleware('auth:sanctum');

    Route::get('/sync/holiday', function () {
        $year = request()->query('year');

        $client = new \GuzzleHttp\Client([
            'verify' => false, // Disable SSL verification
        ]);

        try {
            // Call API to get holiday data
            $response = $client->get("https://dayoffapi.vercel.app/api?year={$year}");
            $data = json_decode($response->getBody(), true);

            foreach ($data as $holiday) {
                \App\Models\Holiday::updateOrCreate([
                    'date' => $holiday['tanggal'],
                    'name' => $holiday['keterangan'],
                    'is_day_off' => $holiday['is_cuti'],
                ]);
            }

            return response()->json([
                'message' => 'Sync holiday success',
                'code' => 200,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'code' => 500,
            ]);
        }
    })->middleware('auth:sanctum');
});