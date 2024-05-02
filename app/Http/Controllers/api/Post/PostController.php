<?php

namespace App\Http\Controllers\Api\Post;

use App\Enums\Distributed_to;
use App\Http\Controllers\Controller;
use App\Http\Requests\PostReactionRequest;
use App\Http\Requests\PostRequest;
use App\Http\Requests\PostUpdateRequest;
use App\Models\Follow;
use App\Models\Media;
use App\Models\Post;
use App\Models\PostAction;
use App\Models\PostActionUser;
use App\Models\PostMedia;
use App\Models\PostReaction;
use App\Models\PostShare;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{

    protected $user;

    public function __construct()
    {
        $this->user = Auth::user();
    }

    
    /**
     * getUserPost
     */
    public function getUserPost(Request $request)
    {
        $this->user = Auth::user();
        if($this->user){
           
            $post = Post::where('user_id', $this->user->id)
                ->orderByDesc('created_at')->paginate(20);
        }
        
        return response()->json([
            'status' => 'sucess',
            'message' => 'post list user connect',
            'code' => '200',
            'data' => $post,
        ]); 
    }

    /**
     * getPostUsers
     */
    public function getPostUsers(Request $request)
    {
        $this->user = Auth::user();
        if($this->user){
            $follows = Follow::where('follower_user_id', $this->user->id)->get();
            $id_follows = [];
            foreach ($follows as $follow){
                array_push($id_follows, $follow->id);
            }
            $post = Post::where('country', $this->user->country)
                ->orWhere('city', $this->user->city)
                ->OrwhereIn(
                    function($query) use($id_follows){
                        $query->whereIn('user_id', $id_follows); 
                    })
                ->orderByDesc('created_at')->paginate(20);
        }
        else{
            $validator = $request->validate([
                'country' => ['required'],
                'city' => ['required'],
            ]);
            $country = $request->country;
            $city = $request->city;
            $post = Post::where('distributed_to', '!=', Distributed_to::FOLLOWERS)
            ->orWhere('country', $country)
            ->orWhere('city', $city)
            ->orderByDesc('created_at')->paginate(20);
        }
        // return $this->getPost();
        return response()->json([
            'status' => 'sucess',
            'message' => 'post list user connect all plateforme',
            'code' => '200',
            'data' => $post,
        ]); 
    }


    /**
     * getPost
     */
    public function getPost(Request $request)
    {
        $validator = $request->validate([
            'id' => ['required'],
        ]);
        $id = $request->id;

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
            'message' => 'post get',
            'code' => '200',
            'data' => $post,
        ]); 
    }

    /**
     * create post
     */
    public function createPost(PostRequest $request)
    {
        $validated = $request->validated();
        $this->user = Auth::user();
        $post = Post::create([
            'user_id'       => $this->user->id,
            'title'     => $request->title,
            'message'       => $request->message,
            'country'       => isset($request->country)? $request->country : null,
            'city'      => isset($request->city)? $request->city : null,
            'distributed_to'        => isset($request->distributed_to)? $request->distributed_to : Distributed_to::PEOPLE,
            'type_id'       => $request->type_id,
            'status'        => isset($request->status)? $request->status : null,
            'start_date'        => isset($request->start_date)? $request->start_date : null,
            'end_date'      => isset($request->end_date)? $request->end_date : null,
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
        if($request->actions){
                    
            foreach ($request->actions as $action) {
                $post_action = PostAction::create([
                    'post_id' => $post->id,
                    'action_id' => $action->id,
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
     * partage post
     */
    public function sharePost(Request $request)
    {
        $validator = $request->validate([
            'post_id' => ['required'],
        ]);
        $post_id = $request->post_id;
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
     * create action(user add action)
     */
    public function addAction(Request $request)
    {
        $validator = $request->validate([
            'user_id' => ['required'],
            'post_id' => ['required'],
            'action_id' => ['required']
        ]);

        $postAction = PostAction::where('post_id', $request->post_id)
        ->with('post')->first();
        if(!$postAction){
            return response()->json([
                'status' => 'failed',
                'message' => 'PostAction n\'exist pas',
                'code' => '404',
                'data' => null,
                ]);
        }

        PostActionUser::createOrUpdate([
            'user_id' => $this->user->id,
            'post_action_id' => $postAction->id,
        ]);

        return response()->json([
            'status' => 'sucess',
            'message' => 'action user ajouter avec succes',
            'code' => '200',
            'data' => $postAction,
            ]);
    }

    /**
     * create reaction post
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
     * delete reaction post 
     */
    public function deleteReaction(Request $request)
    {
        $validator = $request->validate([
            'post_id' => ['required'],
        ]);
        $post_id = $request->post_id;

        $postReaction = PostReaction::where('post_id', $post_id)
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
     * Update post
     */
    public function updatePost(PostUpdateRequest $request)
    {
        $validated = $request->validated();

        $post = Post::where('id', $request->id)
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
            'country'       => isset($request->country)? $request->country : null,
            'city'      => isset($request->city)? $request->city : null,
            'distributed_to'        => isset($request->distributed_to)? $request->distributed_to : null,
            'type_id'       => $request->type_id,
            'status'        => isset($request->status)? $request->status : null,
            'start_date'        => isset($request->start_date)? $request->start_date : null,
            'end_date'      => isset($request->end_date)? $request->end_date : null,
        ]);

        PostAction::where('post_id', $post->id)->delete();

        if($request->actions){
                    
            foreach ($request->actions as $action) {
                $post_action = PostAction::create([
                    'post_id' => $post->id,
                    'action_id' => $action->id,
                ]);
            }
        }

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
     * remove post
     */
    public function deletePost(Request $request)
    {
        $validator = $request->validate([
            'post_id' => ['required'],
        ]);
        $post_id = $request->post_id;

        $post = Post::where('id', $post_id)
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
