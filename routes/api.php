<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\auth\ForgotPasswordController;
use App\Http\Controllers\Api\auth\ResetPasswordController;
use App\Http\Controllers\api\Post\PostCommentsController;
use App\Http\Controllers\Api\Post\PostController;
use App\Http\Controllers\api\ReactionController;
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
    'middleware' => 'auth:sanctum',

], function ($router) {

    Route::get('post', [PostController::class, 'getPost']);
    Route::post('post/create', [PostController::class, 'createPost']);
    Route::put('post/update', [PostController::class, 'updatePost']);
    Route::delete('post/delete', [PostController::class, 'deletePost']);
    Route::post('post/share', [PostController::class, 'sharePost']);
    Route::post('post/reaction/add', [PostController::class, 'addReaction']);
    Route::post('post/action/add', [PostController::class, 'addAction']);
    Route::delete('post/reaction/delete', [PostController::class, 'deleteReaction']);
    Route::get('get/post/comment', [PostCommentsController::class, 'getPostComments']);
    Route::post('create/post/comment', [PostCommentsController::class, 'createComment']);
    Route::post('update/post/comment', [PostCommentsController::class, 'updateComment']);
    Route::post('delete/post/comment', [PostCommentsController::class, 'deleteComment']);
    Route::post('add/comment/reaction', [PostCommentsController::class, 'addCommentReaction']);
    Route::post('update/comment/reaction', [PostCommentsController::class, 'updateCommentReaction']);
    Route::post('delete/comment/reaction', [PostCommentsController::class, 'deleteCommentReaction']);
    Route::get('get/reactions', [ReactionController::class, 'getReactions']);
});
Route::post('login', [AuthController::class, 'login'])->name('login');
Route::post('register', [AuthController::class, 'register'])->name('register');
Route::get('post/user', [PostController::class, 'getUserPost']);

Route::post('password/forgot', [ForgotPasswordController::class, 'forgotPassword']);
Route::post('password/reset', [ResetPasswordController::class, 'resetPassword']);
Route::get('social/login', [AuthController::class, 'socialLogin']);
