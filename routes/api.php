<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\auth\ForgotPasswordController;
use App\Http\Controllers\Api\auth\ResetPasswordController;
use App\Http\Controllers\Api\Post\PostController;
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

Route::group([

    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {

    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::post('me', [AuthController::class, 'me']);
});


Route::group([

    'middleware' => 'api',

], function ($router) {

    Route::get('post/{id}', [PostController::class, 'getPost']);
    Route::post('post/create', [PostController::class, 'createPost']);
    Route::put('post/update/{id}', [PostController::class, 'updatePost']);
    Route::delete('post/delete/{id}', [PostController::class, 'deletePost']);
    Route::post('post/share', [PostController::class, 'sharePost']);
    Route::post('post/reaction/add', [PostController::class, 'addReaction']);
    Route::post('post/action/add', [PostController::class, 'addAction']);
    Route::delete('post/reaction/delete/{id}', [PostController::class, 'deleteReaction']);

});
Route::post('login', [AuthController::class, 'login'])->name('login');
Route::get('post/user', [PostController::class, 'getUserPost']);

Route::post('password/forgot',[ForgotPasswordController::class,'forgotPassword']);
Route::post('password/reset',[ResetPasswordController::class,'resetPassword']);
Route::get('social/login', [AuthController::class, 'socialLogin']);