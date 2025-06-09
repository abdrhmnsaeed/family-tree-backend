<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PersonController;
use App\Http\Controllers\Api\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned the "api" middleware group. Make something great!
|
*/
Route::post('/login', [AuthController::class, 'login']);

Route::post('/people', [PersonController::class, 'store']);
Route::get('/people', [PersonController::class, 'index']);
Route::get('/people/{id}', [PersonController::class, 'show']);
Route::put('/peoples/{id}', [PersonController::class, 'update']);
Route::patch('/people/{id}', [PersonController::class, 'update']);

// Route::middleware('auth:sanctum')->group(function () {
//     Route::post('/people', [PersonController::class, 'store']);
//      Route::get('/people', [PersonController::class, 'index']);
//     Route::get('/people/{id}', [PersonController::class, 'show']);
// });
