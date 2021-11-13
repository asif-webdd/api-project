<?php

use App\Http\Controllers\Api\AuthController;
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


Route::post('v1/auth/login', [AuthController::class, 'login']);

Route::prefix('v1')->middleware('auth:api')->group(function (){
    Route::prefix('users')->group(function (){
        Route::get('/', [AuthController::class, 'index']);
        Route::post('/', [AuthController::class, 'register']);
        Route::get('/{id}', [AuthController::class, 'show']);
        Route::patch('/{id}', [AuthController::class, 'update']);
        Route::delete('/{id}', [AuthController::class, 'destroy']);
    });

    Route::post('/auth/logout', [AuthController::class, 'logout']);
});

Route::fallback(function (){
   return error_response(__('message.failed'));
});






