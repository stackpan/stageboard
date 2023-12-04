<?php

use App\Http\Controllers\Page\ShowBoardPageController;
use App\Http\Controllers\Page\HomePageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Web\BoardWebController;
use App\Http\Controllers\Web\CardWebController;
use App\Http\Controllers\Web\ColumnWebController;
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

// Route::get('/dashboard', function () {
//     return Inertia::render('Dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::name('page.')->group(function () {
        Route::get('/home', HomePageController::class)->name('page.home');
        Route::get('/board/{board}', ShowBoardPageController::class)->name('board.show');
    });

    Route::name('web.')->group(function () {
        Route::apiResource('boards', BoardWebController::class)->only(['store', 'update', 'destroy']);
        Route::apiResource('boards.columns', ColumnWebController::class)->only(['store']);
        Route::apiResource('columns', ColumnWebController::class)->only(['update', 'destroy']);
        Route::patch('/columns/{column}/swap', [ColumnWebController::class, 'swap'])->name('columns.swap');
        Route::apiResource('columns.cards', CardWebController::class)->only(['store']);
        Route::apiResource('cards', CardWebController::class)->only(['update', 'destroy']);
        Route::patch('/cards/{card}/move', [CardWebController::class, 'move'])->name('cards.move');
    });
});

require __DIR__.'/auth.php';
