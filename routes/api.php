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
use App\Http\Controllers\api\FriendRequestController;
use App\Http\Controllers\api\PaymentController;
use App\Http\Controllers\api\Post\PostActionUserController;
use App\Http\Controllers\api\ReactionController;
use App\Http\Controllers\api\StatisticController;
use App\Http\Controllers\Api\User\UserController;
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
Route::get('get/action', [PostController::class, 'getAction']);
Route::get('get/post', [PostController::class, 'getAllPost']);
Route::get('get/all/post', [PostController::class, 'getAlertEvennement']);
Route::get('get/post/financement', [PostController::class, 'getAllPostFinancement']);
Route::get('get/evennement', [PostController::class, 'getAllEvennement']);
Route::get('get/city', [UserController::class, 'getCity']);
Route::get('post/search', [PostController::class, 'getPostSearch']);

Route::middleware(['auth:sanctum', 'online'])->group(function () {
    Route::post('soutien/callback', [PaymentController::class, 'soutienCallback']);
    Route::post('demande/retrait', [PaymentController::class, 'demandeRetrait']);
    
    Route::get('user/posts', [PostController::class, 'getPostsUser']);
    Route::get('user/alertes', [PostController::class, 'getAlerteUsers']);
    Route::get('user/evennements', [PostController::class, 'getEvennementUsers']);
    Route::get('user/alerte/reaction', [ReactionController::class, 'reactionAlerteUser']);
    Route::get('user/evennement/reaction', [ReactionController::class, 'reactionEvennementUser']);
    Route::get('user/post/reaction', [ReactionController::class, 'reactionPostUser']);

    // Routes pour les opérations CRUD sur les post_action_users
    Route::get('/post-action-users', [PostActionUserController::class, 'index']);
    Route::get('/account', [StatisticController::class, 'index']);
    Route::get('historique/don/recu', [StatisticController::class, 'donRecu']);
    Route::get('historique/don/fait', [StatisticController::class, 'donFait']);

    Route::post('create/post-action-users', [PostActionUserController::class, 'store']);
    Route::get('/post-action-users/{postActionUser}', [PostActionUserController::class, 'show']);
    Route::put('upadte/post-action-users/{postActionUser}', [PostActionUserController::class, 'update']);
    Route::delete('delete/post-action-users/{postActionUser}', [PostActionUserController::class, 'destroy']);
    Route::get('list/post-action-users', [PostActionUserController::class, 'postActionUser']);
    
    Route::post('post/create', [PostController::class, 'createPost']);
    Route::post('post/update', [PostController::class, 'updatePost']);
    Route::post('post/delete', [PostController::class, 'deletePost']);
    Route::post('post/share', [PostController::class, 'sharePost']);
    Route::post('post/desactive/alert', [PostController::class, 'desactiveAlert']);
    
    Route::post('post/reaction/add', [PostController::class, 'addReaction']);
    Route::post('post/action/add', [PostController::class, 'addAction']);
    Route::get('list/users/evennement', [PostController::class, 'getUsersEvennement']);
    
    // Route::post('post/action/user', [PostController::class, 'toggleParticipation']);
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

    Route::get('user/notification', [UserController::class, 'notify_user']);
    Route::get('refresh/token/push/notify', [UserController::class, 'refresh_token_notify']);
    
    Route::post('user/notification/markasread', [UserController::class,'markAsRead']);
    Route::post('user/notification/settings', [UserController::class,'notificationSettings']);
    Route::get('user/info', [UserController::class,'infoUser']);

    Route::controller(UserController::class)->group(function () {
        // Route::put('notification/token/refresh', 'refresh_token_notify');
        // Route::get('notifications', 'notifications');
        Route::put('user/update', 'update_info');
        Route::post('user/update/profile', 'pictureProfile');
        Route::post('user/update/cover', 'coverProfil');
        Route::put('user/modifie/password', 'modifyPassword');
    });

    Route::controller(FriendRequestController::class)->group(function () {
        Route::post('friend/send', 'sendFriendRequest');
        Route::post('friend/respond', 'respondToFriendRequest');
        Route::get('friend/pending', 'getFriendRequests');
        Route::get('send/friend/pending', 'sentFriendRequests');
        Route::get('friend/list', 'getFriendsList');
        Route::get('friend/suggestion', 'getSuggestion');
    });

    Route::controller(ConversationController::class)->group(function () {
        Route::post('chat/create', 'createChat');
        Route::post('chat/message/create', 'sendMessage');
        Route::get('chat/list', 'listConversations');
        Route::post('chat/message/list', 'getMessageFor');
        Route::post('chat/verify', 'chatExist');
        Route::post('chat/read/message', 'readMessage');

    });

});

Route::post('reaction/create', [ReactionController::class, 'createReaction']);


Route::post('login', [AuthController::class, 'login'])->name('login');
Route::post('register', [AuthController::class, 'register'])->name('register');


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
   