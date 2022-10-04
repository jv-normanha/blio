<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


/**
 * Rotas de banks
 */
Route::prefix('banks')
    //->middleware(['auth:api'])
    ->group(function () {
            
        Route::get('', [App\Http\Controllers\V1\BanksController::class, 'index']);
        Route::post('/bank', [App\Http\Controllers\V1\banksController::class, 'store']);
        Route::get('{bank}', [App\Http\Controllers\V1\BanksController::class, 'show']);
        Route::put('{budget}', [App\Http\Controllers\BanksController::class, 'update']);
        Route::delete('{budget}', [App\Http\Controllers\BanksController::class, 'destroy']);

    });