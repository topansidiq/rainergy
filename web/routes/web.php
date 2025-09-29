<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\MonitorController;
use App\Http\Controllers\PanelController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->name('auth.')->group(function () {
    Route::get('/login', [AuthController::class, 'show_login'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::get('/signin', [AuthController::class, 'show_signin'])->name('signin');
    Route::post('/signin', [AuthController::class, 'signin'])->name('signin');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/username', [AuthController::class, 'show_username'])->name('show_username');
});
Route::middleware('auth')->group(function () {
    Route::prefix('profile')->name('auth.')->group(function () {
        Route::put('/username/{user}', [AuthController::class, 'username'])->name('username');
    });

    Route::prefix('monitor')->name('monitor.')->group(function () {
        Route::get('/', [MonitorController::class, "index"])->name('dashbboard');
        Route::get('/panels', [MonitorController::class, "panel"])->name('panels');
        Route::get('/panels/log/{panel_id}', [PanelController::class, "log"])->name('panels.log');
        Route::get('/units', [MonitorController::class, "unit"])->name('units');
    });
});
