<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BoardApiController;
use App\Http\Controllers\Api\ColumnApiController;
use App\Http\Controllers\Api\CardApiController;

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
    Route::apiResource('boards', BoardApiController::class);
    Route::apiResource('boards.columns', ColumnApiController::class)->only(['index', 'store']);
    Route::apiResource('columns', ColumnApiController::class)->except(['index', 'store']);
    Route::patch('/columns/{column}/swap', [ColumnApiController::class, 'swap'])->name('columns.swap');
    Route::apiResource('columns.cards', CardApiController::class)->only(['index', 'store']);
    Route::apiResource('cards', CardApiController::class)->except(['index', 'store']);
    Route::patch('/cards/{card}/move', [CardApiController::class, 'move'])->name('cards.move');
});
