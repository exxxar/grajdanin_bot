<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\MessageController;
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



Route::post('/auth/init', [AuthController::class, 'guest']);

// Гостевая авторизация (создание или восстановление гостя)
Route::post('/auth/guest', [AuthController::class, 'guest']);

// Классический вход
Route::post('/auth/login', [AuthController::class, 'login']);

// Выход
Route::post('/auth/logout', [AuthController::class, 'logout'])
    ->middleware('auth:sanctum');


Route::post('/auth/upgrade', [AuthController::class, 'upgrade'])
    ->middleware('auth:sanctum');

// Получить текущего пользователя
Route::get('/auth/me', [AuthController::class, 'me'])
    ->middleware('auth:sanctum');




Route::middleware('auth:sanctum')->group(function () {
    Route::get('/messages/{report}', [MessageController::class, 'index']);
    Route::post('/messages', [MessageController::class, 'store']);
    Route::get('/chats', [MessageController::class, 'userChats']);
    Route::get('/chat/{chatId}/messages', [MessageController::class, 'chatMessages']);
    Route::get('/message/{message}', [MessageController::class, 'show']);
    Route::delete('/message/{message}', [MessageController::class, 'destroy']);
});




