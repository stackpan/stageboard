<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BoardController;
use App\Http\Controllers\Api\ColumnController;
use App\Http\Controllers\Api\CardController;

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

Route::middleware('auth')->group(function () {
    Route::apiResource('boards', BoardController::class);
    Route::apiResource('boards.columns', ColumnController::class);
    Route::post('/boards/{board}/columns/{column}/move', [ColumnController::class, 'move'])->name('boards.columns.move');
    Route::apiResource('boards.columns.cards', CardController::class);
    Route::post('/boards/{board}/columns/{column}/cards/{card}/move', [CardController::class, 'move'])->name('boards.columns.cards.move');
});
