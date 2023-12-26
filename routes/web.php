<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Web\BoardCollaboratorController;
use App\Http\Controllers\Web\BoardWebController;
use App\Http\Controllers\Web\CardWebController;
use App\Http\Controllers\Web\ColumnWebController;
use App\Http\Controllers\Web\Page\HomePageController;
use App\Http\Controllers\Web\Page\ShowBoardPageController;
use App\Http\Controllers\Web\UserController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::name('web.')->group(function () {
        Route::apiResource('boards', BoardWebController::class)->except(['index']);
        Route::apiSingleton('boards.collaborators', BoardCollaboratorController::class)->creatable()->except(['update']);

        Route::apiResource('boards.columns', ColumnWebController::class)->shallow()->except(['index']);
        Route::patch('/columns/{column}/swap', [ColumnWebController::class, 'swap'])->name('columns.swap');

        Route::apiResource('columns.cards', CardWebController::class)->shallow()->except(['index']);
        Route::patch('/cards/{card}/move', [CardWebController::class, 'move'])->name('cards.move');

        Route::get('/users/search', [UserController::class, 'search'])->name('users.search')->middleware(['throttle:search']);

        Route::name('page.')->group(function () {
            Route::get('/home', HomePageController::class)->name('home');
            Route::get('/board/{board}', ShowBoardPageController::class)->name('board.show');
        });
    });
});

require __DIR__.'/auth.php';
