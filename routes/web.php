<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\PeramalanController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('tes-download/{peramalan}/export', [PeramalanController::class, 'export']);

Route::get('/{any?}', function () {
    return view('app');
})->where('any', '[\/\w\.-]*');