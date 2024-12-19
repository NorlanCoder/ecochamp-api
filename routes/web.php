<?php

use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\ParticipationController;
use App\Http\Controllers\Web\RetraitController;
use App\Http\Controllers\Web\UserController;
use Illuminate\Support\Facades\Route;


Route::get('web/login', [AuthController::class, 'login'])->name('web.login');
Route::post('web_login/check', [AuthController::class, 'check'])->name('web.auth.check');

Route::middleware(['web_dashboard'])->group(function () {
    Route::get('web/dashboard', [AuthController::class, 'dashboard'])->name('web.dashboard');
    Route::get('web/logout', [AuthController::class, 'logout'])->name('web.auth.logout');

    Route::controller(UserController::class)->group(function () {
        Route::get('admin/index', 'index_admin')->name('web.admin.index'); 
        Route::get('user/index', 'index')->name('web.user.index'); 
        Route::get('ong/index', 'index_ong')->name('web.ong.index'); 
        Route::get('user/{user?}/profile', 'profile')->name('web.user.profile'); 
        Route::post('admin/create', 'store')->name('web.admin.store');
        Route::put('admin/update/{user}', 'update')->name('web.admin.update');
        Route::get('admin/delete/{user}', 'destroy')->name('web.admin.delete');
    });

    Route::controller(ParticipationController::class)->group(function () {
        Route::get('financement/index', 'financement')->name('web.financement.index'); 
        Route::get('participation/index', 'participation')->name('web.participation.index'); 
        Route::get('benevolat/index', 'benevolat')->name('web.benevolat.index'); 
       
    });

    Route::controller(RetraitController::class)->group(function () {
        Route::get('retrait/index', 'index')->name('web.retrait.index'); 
        Route::get('retrait/demande/{demande?}', 'demande')->name('web.retrait.demande'); 
        
    });


});
