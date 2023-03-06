<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::name('api.')
    ->namespace('App\Http\Controllers\Api')
    ->group(function() {

    Route::get('convert', 'ConvertController@convert')
        ->name('convert');

    // Catch other methods
    Route::match(['post', 'put', 'patch', 'delete', 'options'], 'convert', 'ApiErrorController@onlyGetMethodAllowed');

    // Catch other methods
    // Route::controller(ApiErrorController::class)->group(function (Request $request) {
    //     Route::post('convert', 'onlyGetMethodAllowed');
    //     Route::('convert', 'onlyGetMethodAllowed');
    // });
});
