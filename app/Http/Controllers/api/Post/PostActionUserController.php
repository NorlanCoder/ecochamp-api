<?php

namespace App\Http\Controllers\api\Post;

use App\Http\Controllers\Controller;
use App\Models\PostActionUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostActionUserController extends Controller
{
     /**
     * Liste des particitions et etat
     */
     public function index()
     {
        $postActionUsers = PostActionUser::where('user_id', Auth::id())->get();

         return response()->json(
            [   
                'code' =>200,
                'success' => true,
                'data' => $postActionUsers,
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
            //  'remove' => 'boolean',
         ]);
 
         if ($request->post_actions){
            foreach ($request->post_actions as $post_action_id) {
                $postActionUser = PostActionUser::create([
                    'post_action_id' => $post_action_id,
                    'user_id' => Auth::id(), 
                    'remove' => $request->remove ?? false,
                ]);
            }
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
 
         $postActionUser->remove = true; 
         $postActionUser->save();
 
        return response()->json(
            [   
                'code' =>200,
                'success' => true,
                'message' => 'Deleted successfully'
            ]
        );
     }
}            