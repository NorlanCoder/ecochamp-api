<?php

namespace App\Http\Controllers\Api\Post;

use App\Http\Controllers\Controller;
use App\Http\Requests\PostReactionRequest;
use App\Http\Requests\PostRequest;
use App\Models\Media;
use App\Models\Post;
use App\Models\PostMedia;
use App\Models\PostReaction;
use App\Models\PostShare;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{

    protected $user;

    public function __construct()
    {
        $this->user = Auth::user();
    }

    
    /**
     * Display a listing of the resource.
     */
    public function getUserPost()
    {
        //
    }

    /**
     * Display a listing of the resource.
     */
    public function getPost(string $id)
    {
        $post = Post::where('id', $id)
            ->with('user')->with('postMedias')
            ->with('postReactions')
            ->with('tags')->first();
        if(!$post){
            return response()->json([
                'status' => 'failed',
                'message' => 'post n\'exist pas',
                'code' => '404',
                'data' => null,
            ]); 
        }
        return response()->json([
            'status' => 'sucess',
            'message' => 'post delete',
            'code' => '200',
            'data' => $post,
        ]); 
    }

    /**
     * Show the form for creating a new resource.
     */
    public function createPost(PostRequest $request)
    {
        $validated = $request->validated();

        $post = Post::create([
            'user_id'       => $this->user->id,
            'title'     => $request->title,
            'message'       => $request->message,
            'country'       => isset($request->country)? $request->country : nullValue(),
            'city'      => isset($request->city)? $request->city : nullValue(),
            'distributed_to'        => isset($request->distributed_to)? $request->distributed_to : nullValue(),
            'type_id'       => $request->type_id,
            'status'        => isset($request->status)? $request->status : nullValue(),
            'start_date'        => isset($request->start_date)? $request->start_date : nullValue(),
            'end_date'      => isset($request->end_date)? $request->end_date : nullValue(),
        ]);
        if ($request->tags){
            foreach ($request->tags as $label) {
                $new_tag = Tag::where('label', $label);
                if(!$new_tag){
                    $new_tag = Tag::create(['label' => $label, 'count' => 0]);
                }
                $new_tag->count += 1;
                $new_tag->save();
            }
        }
        if($request->medias){
                    
            foreach ($request->images as $image) {
                $img = time() . $image->getClientOriginalName();
                $path = $image->move(public_path() . "\post", $img);
                $media = Media::create([
                    'madia_url' => $path,
                ]);
                PostMedia::create([
                    'post_id' => $post->id,
                    'media_id' => $media->id,
                ]);

            }
        }
        return response()->json([
            'status' => 'sucess',
            'message' => 'post create',
            'code' => '200',
            'data' => $post,
        ]); 
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function sharePost(string $post_id)
    {
        $post = Post::where('id', $post_id)->first();
        PostShare::create([
            'post_id' => $post->id,
            'user_id' => $this->user->id,
            ]);

        return response()->json([
            'status' => 'sucess',
            'message' => 'post partage',
            'code' => '200',
            'data' => $post,
            ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function addReaction(PostReactionRequest $request)
    {
        $post = Post::where('id', $request->post_id)->first();
        if(!$post){
            return response()->json([
                'status' => 'failed',
                'message' => 'post n\'exist pas',
                'code' => '404',
                'data' => null,
                ]);
        }

        PostReaction::createOrupdate([
            'user_id' => $this->user->id,
            'post_id' => $post->id,
            'reaction_id' => $request->reaction_id,
        ]);

        return response()->json([
            'status' => 'sucess',
            'message' => 'reaction ajouter avec succes',
            'code' => '200',
            'data' => $post,
            ]);
    }

    /**
     * Display the specified resource.
     */
    public function deleteReaction(string $id)
    {
        $postReaction = PostReaction::where('post_id', $id)
            ->where('user_id', $this->user->id)->first();
        if(!$postReaction){
            return response()->json([
                'status' => 'failed',
                'message' => 'postReaction n\'exist pas',
                'code' => '404',
                'data' => null,
            ]); 
        }
        $postReaction->delete();
        return response()->json([
            'status' => 'sucess',
            'message' => 'postReaction delete',
            'code' => '200',
            'data' => null,
        ]); 
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function updatePost(Request $request, string $id)
    {
        $validated = $request->validated();

        $post = Post::where('id', $id)
            ->where('user_id', $this->user->id)->first();
        if(!$post){
            return response()->json([
                'status' => 'failed',
                'message' => 'post n\'exist pas',
                'code' => '404',
                'data' => null,
            ]); 
        }
        $post->upadte([
            'user_id'       => $this->user->id,
            'title'     => $request->title,
            'message'       => $request->message,
            'country'       => isset($request->country)? $request->country : nullValue(),
            'city'      => isset($request->city)? $request->city : nullValue(),
            'distributed_to'        => isset($request->distributed_to)? $request->distributed_to : nullValue(),
            'type_id'       => $request->type_id,
            'status'        => isset($request->status)? $request->status : nullValue(),
            'start_date'        => isset($request->start_date)? $request->start_date : nullValue(),
            'end_date'      => isset($request->end_date)? $request->end_date : nullValue(),
        ]);

        if ($request->tags){
            foreach ($request->tags as $label) {
                $new_tag = Tag::where('label', $label);
                if(!$new_tag){
                    $new_tag = Tag::create(['label' => $label, 'count' => 0]);
                }
                $new_tag->count += 1;
                $new_tag->save();
            }
        }

        PostMedia::where('post_id', $post->id)->delete();

        if($request->medias){
                    
            foreach ($request->images as $image) {
                $img = time() . $image->getClientOriginalName();
                $path = $image->move(public_path() . "\post", $img);
                $media = Media::create([
                    'madia_url' => $path,
                ]);
                PostMedia::create([
                    'post_id' => $post->id,
                    'media_id' => $media->id,
                ]);

            }
        }
        return response()->json([
            'status' => 'sucess',
            'message' => 'post mise à jour',
            'code' => '200',
            'data' => $post,
        ]); 
    }

    /**
     * Remove the specified resource from storage.
     */
    public function deletePost(string $id)
    {
        $post = Post::where('id', $id)
            ->where('user_id', $this->user->id)->first();
        if(!$post){
            return response()->json([
                'status' => 'failed',
                'message' => 'post n\'exist pas',
                'code' => '404',
                'data' => null,
            ]); 
        }
        $post->delete();
        return response()->json([
            'status' => 'sucess',
            'message' => 'post delete',
            'code' => '200',
            'data' => null,
        ]); 
    }
}
