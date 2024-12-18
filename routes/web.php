<?php

use App\Http\Controllers\Web\AuthController;
use Illuminate\Support\Facades\Route;


Route::get('web/login', [AuthController::class, 'login'])->name('web.login');
Route::post('web_login/check', [AuthController::class, 'check'])->name('web.auth.check');

Route::middleware(['web'])->group(function () {
    Route::get('web/logout', [AuthController::class, 'logout'])->name('web.auth.logout');
    Route::get('web/dashboard', [AuthController::class, 'dashboard'])->name('web.dashboard');
});
