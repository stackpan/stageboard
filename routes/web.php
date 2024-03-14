<?php

use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Web\BoardCollaboratorController;
use App\Http\Controllers\Web\BoardWebController;
use App\Http\Controllers\Web\CardWebController;
use App\Http\Controllers\Web\ColumnWebController;
use App\Http\Controllers\Web\Page\HomePageController;
use App\Http\Controllers\Web\Page\BoardPageController;
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
    return to_route('web.page.home');
});

Route::middleware('guest')->group(function () {
    Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
    Route::get('/board/{boardAlias}/public', [BoardPageController::class, 'showPublic'])->name('web.page.board.show.public');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::name('web.')->group(function () {
        Route::apiResource('boards', BoardWebController::class)->except(['index']);
        Route::apiSingleton('boards.collaborators', BoardCollaboratorController::class)->creatable();

        Route::apiResource('boards.columns', ColumnWebController::class)->shallow()->except(['index']);
        Route::post('/boards/{board}/columns/generate', [ColumnWebController::class, 'generate'])->name('boards.columns.generate');
        Route::patch('/columns/{column}/swap', [ColumnWebController::class, 'swap'])->name('columns.swap');

        Route::apiResource('columns.cards', CardWebController::class)->shallow()->except(['index']);
        Route::patch('/cards/{card}/move', [CardWebController::class, 'move'])->name('cards.move');

        Route::get('/users/search', [UserController::class, 'search'])->name('users.search')->middleware(['throttle:search']);

        Route::name('page.')->group(function () {
            Route::get('/home', HomePageController::class)->name('home');
            Route::get('/board/{boardAlias}', [BoardPageController::class, 'show'])->name('board.show');
            Route::get('/board/{boardAlias}/settings', [BoardPageController::class, 'edit'])->name('board.edit');
        });
    });
});

require __DIR__.'/auth.php';
