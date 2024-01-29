<?php

use App\Http\Controllers\User\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Qdd\QddController;

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
Route::post('/user/cadastrar', [UserController::class, 'create']);
Route::post('/user/logar', [UserController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user/list', [UserController::class, 'getUsers']);
    
});


