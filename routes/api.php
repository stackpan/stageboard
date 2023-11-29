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

Route::middleware('auth')->name('api.')->group(function () {
    Route::apiResource('boards', BoardController::class);
    Route::apiResource('boards.columns', ColumnController::class)->only(['index', 'store']);
    Route::apiResource('columns', ColumnController::class)->except(['index', 'store']);
    Route::patch('/columns/{column}/swap', [ColumnController::class, 'swap'])->name('columns.swap');
    Route::apiResource('columns.cards', CardController::class)->only(['index', 'store']);
    Route::apiResource('cards', CardController::class)->except(['index', 'store']);
    Route::patch('/cards/{card}/move', [CardController::class, 'move'])->name('cards.move');
});
