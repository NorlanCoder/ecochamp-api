<?php

namespace App\Http\Controllers\api\Post;

use App\Enums\NotificationType;
use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\PostAction;
use App\Models\PostActionUser;
use App\Models\PostMedia;
use App\Notifications\ParticipantNotification;
use App\Notifications\UserNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class PostActionUserController extends Controller
{
     /**
     * Liste des particitions et etat
     */
     public function index()
     {
        $postAction_ids = PostActionUser::where('user_id', Auth::id())
            ->get()->unique('post_action_id')->pluck('post_action_id');

            $post_actions = DB::table('post_actions')->whereIn('id', $postAction_ids)->get()->unique('post_id');

            $posts = [];
            if($post_actions) {
                foreach ($post_actions as $post_action) {
                    $post_id = $post_action->post_id;
                    $post = Post::where('id', $post_id)->first();
                    
                    if ($post_action) {
                        $post_action_id = $post_action->id;
                    }
                    $post_action_user = PostActionUser::where('user_id', Auth::id())->where('post_action_id', $post_action_id)->first();
                    if ($post_action_user) {
                        $post_actions = DB::table('post_actions')->whereIn('id', $postAction_ids)->get();
                        $post_action_user->post_action = $post_actions;
                        $post_action_user->post_id = $post_id;
                    
                        $post = Post::where('id', $post_id)
                            ->with('user')
                            ->with('comments')
                            ->with('tags')
                            ->with('postReactionsWithoutRemove')
                            ->with('postActions')
                            ->first();
                        $images = PostMedia::where('post_id', $post->id)
                            ->with('media')
                            ->get()
                            ->map(function ($postMedia) {
                                return $postMedia->media->url_media;
                            });
                        $post->images = $images;
                        $posts[] = [
                            'post_action_user' => $post_action_user,
                            'post' => $post
                        ];
                    }else {
                        // Créer un nouvel enregistrement si aucun n'existe
                        $post_action_user = new PostActionUser();
                        $post_action_user->user_id = Auth::id();
                        $post_action_user->post_action_id = $post_action_id;
                        $post_action_user->post_id = $post_id;
                
                        $posts[] = [
                            'post_action_user' => $post_action_user,
                            'post' => $post
                        ];
                    }
                }

            }

         return response()->json(
            [   
                'code' =>200,
                'success' => true,
                'data' => $posts,
            ]
        );
     }

     /**
     * Liste des particitions sans Post
     */
    public function postActionUser()
    {
        $postAction_ids = PostActionUser::where('user_id', Auth::id())
            ->get()->unique('post_action_id')->pluck('post_action_id');

        $post_actions = DB::table('post_actions')->whereIn('id', $postAction_ids)->get()->unique('post_id');


            $posts = [];
            if($post_actions) {
                foreach ($post_actions as $post_action) {
                    // $post = Post::where('id', $post_id)->first();
                    if ($post_action) {
                        $post_action_id = $post_action->id;
                    }
                    
                    $post_action_user = PostActionUser::where('user_id', Auth::id())->where('post_action_id', $post_action_id)->first();
                    if ($post_action_user) {
                        $post_actions = DB::table('post_actions')->whereIn('id', $postAction_ids)->get();
                        $post_action_user->post_action = $post_actions;
                        $post_action_user->post_id = $post_action->post_id;
                        $posts[] = [
                            'post_action_user' => $post_action_user,
                        ];

                    }else {
                        // Créer un nouvel enregistrement si aucun n'existe
                        $post_action_user = new PostActionUser();
                        $post_action_user->user_id = Auth::id();
                        $post_action_user->post_action_id = $post_action_id;
                        $post_action_user->post_id = $post_action->post_id;
                
                        $posts[] = [
                            'post_action_user' => $post_action_user,
                        ];
                    }
                }
            }

        return response()->json(
            [   
                'code' =>200,
                'success' => true,
                'data' => $posts,
            ]
        );
    }

 
     /**
     * create action user post
     */
     public function store(Request $request)
     {
         $request->validate([
             'post_actions.*' => 'required|exists:post_actions,id',
             'post_id' => 'required|exists:posts,id'
            //  'remove' => 'boolean',
         ]);
 
         if ($request->post_actions){
            $post_action_ids = DB::table('post_actions')->where('post_id', $request->post_id)->get()->pluck('id');
            $postActionUser = PostActionUser::where('user_id', Auth::id())
                    ->whereIn('post_action_id', $post_action_ids)->get();
            foreach ($postActionUser as $post_action_user) {
                $post_action_user->delete();
            }
            foreach ($request->post_actions as $post_action_id) {
                $postActionUser = PostActionUser::create([
                    'post_action_id' => $post_action_id,
                    'user_id' => Auth::id(), 
                    'remove' => $request->remove ?? false,
                ]);
            }
        }
 
        // Envoi de la notification par email
        try {
            $post = Post::where("id", $request->post_id)->first();
            $post->user->notify(new UserNotification(NotificationType::Action, 'Vous avez un nouveau participant à votre événement.', Auth::user()->fullname, $post->id));

            Notification::send($post->user, new ParticipantNotification($post->user, Auth::user()));
        } catch (\Throwable $th) {
            // Enregistrement de l'erreur dans les logs
            Log::error('Échec de l\'envoi de la notification.', [
                'user_id' => $user->id ?? null,
                'email' => $user->email ?? 'Adresse email non spécifiée',
                'exception_message' => $th->getMessage(),
                'trace' => $th->getTraceAsString()
            ]);
        }
         return response()->json(
            [   
                'code' =>200,
                'success' => true,
                'data' => $postActionUser,
            ]);
     }
 
    /**
     * voir action post
     */
     public function show(PostActionUser $postActionUser)
     {
         
         if ($postActionUser->user_id != Auth::id()) {
             return response()->json(['error' => 'Unauthorized'], 403);
         }
 
         return response()->json(
            [   
                'code' =>200,
                'success' => true,
                'data' => $postActionUser,
            ]
        );
     }
 
     /**
     * Update Action Post
     */
     public function update(Request $request, PostActionUser $postActionUser)
     {
         // Vérifie si l'utilisateur est bien celui qui a créé l'entrée
         if ($postActionUser->user_id != Auth::id()) {
             return response()->json(['error' => 'Unauthorized'], 403);
         }
 
         $request->validate([
             'post_action_id' => 'required|exists:post_actions,id',
             'remove' => 'boolean',
         ]);
 
         $postActionUser->update([
             'post_action_id' => $request->post_action_id,
             'remove' => $request->remove ?? $postActionUser->remove,
         ]);
 
         return response()->json([   
            'code' =>200,
            'success' => true,
            'data' => $postActionUser,
        ]);
     }
 
     /**
     * Delete Action post user
     */

     public function destroy(PostActionUser $postActionUser)
     {
         if ($postActionUser->user_id != Auth::id()) {
             return response()->json(['error' => 'Unauthorized'], 403);
         }
 
         $postActionUser->delete();
 
        return response()->json(
            [   
                'code' =>200,
                'success' => true,
                'message' => 'Deleted successfully'
            ]
        );
    }
}            