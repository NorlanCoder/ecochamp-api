<?php

namespace App\Http\Controllers\api;

use App\Enums\PostType;
use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\PostMedia;
use App\Models\PostReaction;
use App\Models\Reaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReactionController extends Controller
{
    protected Reaction $reaction;

    public function __construct(Reaction $reaction)
    {
        $this->reaction = $reaction;
    }
    /**
     * get list of the reaction.
     */
    public function getReactions()
    {
        $reactions = Reaction::all();
        return response()->json([
            'success' => true,
            'data' => $reactions
        ], 200);
    }

    /**
     * create of the reaction.
     */
    public function createReaction(Request $request)
    {
        $validator = $request->validate([
            'name'  => ['required'],
            'icone'  => ['required']
        ]);
        $reaction = Reaction::create([
            'name'  => $request->name,
            'icone'  => $request->icone
        ]);
        return response()->json([
            'success' => true,
            'data' => $reaction
        ], 200);
    }


    /**
     * reaction Alerte User
     */
    public function reactionAlerteUser()
    {
        $user = Auth::user();
        if($user){
            $post_id = Post::where('type', PostType::Alerte)->select('id')->get();
            $reactions = PostReaction::where('user_id', $user->id)
                ->where('remove', false)
                ->whereIn('post_id', $post_id)
                ->with('post')
            ->orderByDesc('created_at')->paginate(20);
           
            $reactions->getCollection()->transform(function($query) {
                $images = PostMedia::where('post_id', $query->post_id)
                        ->with('media')
                        ->get()
                        ->map(function ($postMedia) {
                            return $postMedia->media->url_media;
                        });
            
                $query->post->images = $images;
                $query->post->user = User::where('id', $query->user_id)->first();
            
                return $query;
            });
               
            return response()->json([
                'status' => 'sucess',
                'message' => 'post list user connect all plateforme',
                'code' => 200,
                'data' => $reactions,
            ]); 
        }
        
    }

    /**
     * reaction Evennement User
     */
    public function reactionEvennementUser()
    {
        $user = Auth::user();
        if($user){
            $post_id = Post::where('type', PostType::Evennement)->select('id')->get();
            $reactions = PostReaction::where('user_id', $user->id)
                ->where('remove', false)
                ->whereIn('post_id', $post_id)
                ->with('post')
            ->orderByDesc('created_at')->paginate(20);
           
            $reactions->getCollection()->transform(function($query) {
                $images = PostMedia::where('post_id', $query->post_id)
                        ->with('media')
                        ->get()
                        ->map(function ($postMedia) {
                            return $postMedia->media->url_media;
                        });
            
                $query->post->images = $images;
                $query->post->user = User::where('id', $query->user_id)->first();
            
                return $query;
            });
               
            return response()->json([
                'status' => 'sucess',
                'message' => 'post list user connect all plateforme',
                'code' => 200,
                'data' => $reactions,
            ]); 
        }
        
    }

    /**
     * reaction Poste User
     */
    public function reactionPostUser()
    {
        $user = Auth::user();
        if($user){
            $post_id = Post::where('type', PostType::Alerte)->select('id')->get();
            $reactions = PostReaction::where('user_id', $user->id)
                ->where('remove', false)
                ->whereIn('post_id', $post_id)
                ->with('post')
            ->orderByDesc('created_at')->paginate(20);
           
            $reactions->getCollection()->transform(function($query) {
                $images = PostMedia::where('post_id', $query->post_id)
                        ->with('media')
                        ->get()
                        ->map(function ($postMedia) {
                            return $postMedia->media->url_media;
                        });
            
                $query->post->images = $images;
                $query->post->user = User::where('id', $query->user_id)->first();
            
                return $query;
            });
               
            return response()->json([
                'status' => 'sucess',
                'message' => 'post list user connect all plateforme',
                'code' => 200,
                'data' => $reactions,
            ]); 
        }
        
    }

}
