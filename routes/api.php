<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\VaksinController;
use App\Http\Controllers\API\PeramalanController;
use App\Http\Controllers\API\OptionsController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// generate url dokumen
Route::get('upload/{pathA}/{pathB}/{pathC?}', function ($pathA, $pathB, $pathC = null) {
    $path = "{$pathA}/{$pathB}";
    if ($pathC !== null) $path .= "/{$pathC}";
    $mime = Storage::mimeType($path);
    $allowedMime = ['image/jpeg', 'image/png', 'image/gif', 'application/pdf', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/msword', 'application/vnd.ms-excel'];

    if (!in_array($mime, $allowedMime))
        return response()->json(['error' => 'Tidak terpenuhi.'], 400);
    else
        return response()->file(storage_path("app/{$path}"));
});
// end generate url dokumen

Route::prefix('account')
->controller(AuthController::class)
->group(function () {
    Route::post('issue-token', 'issueToken');
    Route::get('/', 'getMe')->middleware('auth:sanctum');
    Route::post('update', 'updateProfil')->middleware('auth:sanctum');
    Route::post('revoke-token', 'revokeToken')->middleware('auth:sanctum');
});

Route::middleware('auth:sanctum')
->group(function() {

    Route::prefix('vaksin')
    ->controller(VaksinController::class)
    ->group(function() {
        Route::get('/', 'index');
        Route::get('/{vaksin}/show', 'show');
        Route::get('/{vaksin}/list-per-bulan', 'listByBulan');
        Route::post('/', 'store');
        Route::post('/{vaksin}/update', 'update');
        Route::post('/{vaksin}/update-transaksi', 'updateTransaksi');
        Route::post('/{vaksin}/multidelete-transaksi', 'destroyTransaksi');
        Route::delete('/{vaksin}/delete', 'destroy');
    });

    Route::prefix('peramalan')
    ->controller(PeramalanController::class)
    ->group(function() {
        Route::get('/', 'index');
        Route::get('/{peramalan}/show', 'show');
        Route::post('/get-forecast', 'getForecast');
        Route::post('/', 'store');
        Route::delete('/{peramalan}/delete', 'destroy');
    });

    Route::prefix('opt')
    ->controller(OptionsController::class)
    ->group(function() {
        Route::get('vaksin', 'vaksin');
        Route::get('bulan', 'bulan');
        Route::get('tahun', 'tahun');
    });
});
