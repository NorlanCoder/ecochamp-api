<?php

use App\Http\Controllers\api\Action\ActionController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\auth\ForgotPasswordController;
use App\Http\Controllers\Api\auth\ResetPasswordController;
use App\Http\Controllers\api\Chat\ConversationController;
use App\Http\Controllers\api\Post\PostCommentsController;
use App\Http\Controllers\Api\Post\PostController;
use App\Http\Controllers\Api\Post\PostTypeController;
// use App\Http\Controllers\Api\Chat\ConversationController;
use App\Http\Controllers\Api\Chat\MessageController;
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
    'middleware' => 'auth:sanctum',
    'prefix' => 'auth'

], function ($router) {

    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::post('me', [AuthController::class, 'me']);
});

Route::get('post', [PostController::class, 'getPost']);
Route::get('get/alerte', [PostController::class, 'getAllAlerte']);

Route::middleware(['auth:sanctum'])->group(function () {

    Route::post('post/create', [PostController::class, 'createPost']);
    Route::post('post/update', [PostController::class, 'updatePost']);
    Route::post('post/delete', [PostController::class, 'deletePost']);
    Route::post('post/share', [PostController::class, 'sharePost']);
    Route::post('post/reaction/add', [PostController::class, 'addReaction']);
    Route::post('post/action/add', [PostController::class, 'addAction']);
    Route::post('post/reaction/delete', [PostController::class, 'deleteReaction']);
    Route::get('get/post/comment', [PostCommentsController::class, 'getPostComments']);
    Route::post('create/post/comment', [PostCommentsController::class, 'createComment']);
    Route::post('update/post/comment', [PostCommentsController::class, 'updateComment']);
    Route::post('delete/post/comment', [PostCommentsController::class, 'deleteComment']);
    Route::post('add/comment/reaction', [PostCommentsController::class, 'addCommentReaction']);
    Route::post('update/comment/reaction', [PostCommentsController::class, 'updateCommentReaction']);
    Route::post('delete/comment/reaction', [PostCommentsController::class, 'deleteCommentReaction']);
    Route::get('get/reactions', [ReactionController::class, 'getReactions']);
    Route::post('user/posts', [PostController::class, 'getUserPost']);
    
    //Conversation controller
    Route::get('conversation/list', [ConversationController::class, 'listConversations']);
    Route::get('conversation/message/list/for', [ConversationController::class, 'getMessageFor']);
    Route::post('conversation/message/send', [ConversationController::class, 'sendMessage']);

});

Route::post('reaction/create', [ReactionController::class, 'createReaction']);


Route::post('login', [AuthController::class, 'login'])->name('login');
Route::post('register', [AuthController::class, 'register'])->name('register');
Route::post('posts/users', [PostController::class, 'getPostUsers']);

//// PostType
Route::get('postType/list', [PostTypeController::class, 'postTypeList']);
Route::post('postType/create', [PostTypeController::class, 'createPostType']);
Route::post('postType/update', [PostTypeController::class, 'updatePostType']);
Route::post('postType/delete', [PostTypeController::class, 'deletePostType']);
 

//// Action post
Route::get('action/list', [ActionController::class, 'listAction']);
Route::post('action/create', [ActionController::class, 'createPostAction']);
Route::post('action/update', [ActionController::class, 'updateAction']);
Route::post('action/delete', [ActionController::class, 'deleteAction']);
 

Route::controller(AuthController::class)->group(function () {
    Route::post('password/email',  'forgotPassword');
    Route::post('password/code/check', 'codeCheck');
    Route::post('password/reset', 'resetPassword');
});

Route::get('social/login', [AuthController::class, 'socialLogin']);




