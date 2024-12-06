<?php

namespace App\Http\Controllers\Api\Post;

use App\Enums\Distributed_to;
use App\Enums\NotificationType;
use App\Enums\PostType;
use App\Http\Controllers\Controller;
use App\Http\Requests\PostReactionRequest;
use App\Http\Requests\PostRequest;
use App\Http\Requests\PostUpdateRequest;
use App\Models\Action;
use App\Models\Follow;
use App\Models\FriendRequest;
use App\Models\Media;
use App\Models\Post;
use App\Models\User;
use App\Models\PostAction;
use App\Models\PostActionUser;
use App\Models\PostMedia;
use App\Models\PostReaction;
use App\Models\PostShare;
use App\Models\Tag;
use App\Notifications\UserNotification;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Http\Request;
use Illuminate\Log\Logger;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{

    protected $user;

    public function __construct()
    {
        $this->user = Auth::user();
    }


    /**
     * getUserPost:: Envoi d'id de l'utilisateur 'user_id'
     */
    public function getUserPost(Request $request) {
        $request->validate([
            'user_id' => 'integer|exists:users,id',
        ]);

        // Récupération de l'utilisateur
        $this->user = $request->user_id ? User::findOrFail($request->user_id) : Auth::user();

        if ($this->user) {
            // Récupération des posts
            $post_count = Post::where('user_id', $this->user->id)
                ->count();

            $action_count = 0;

            $posts = Post::where('user_id', $this->user->id)
                ->with([ 'comments', 'postReactionsWithoutRemove', 'tags'])
                ->orderByDesc('created_at')
                ->paginate(10);

            // Récupération de l'état de la demande d'amis
            $friendRequest = FriendRequest::where(function($query) {
                $query->where('sender_id', auth()->id())
                      ->orWhere('receiver_id', auth()->id());
            })->where(function($query) {
                $query->where('sender_id', $this->user->id)
                      ->orWhere('receiver_id', $this->user->id);
            })->first();

            $this->user->friend_status = $friendRequest ? $friendRequest->status : null;


            // Transformation des posts pour ajouter les images
            $posts->getCollection()->transform(function($query) {
                $images = PostMedia::where('post_id', $query->id)
                    ->with('media')
                    ->get()
                    ->map(function ($postMedia) {
                        return $postMedia->media->url_media;
                    });

                $query->images = $images;
                return $query;
            });

        }

        return response()->json([
            'status' => 'success',
            'message' => 'Post list retrieved successfully.',
            'code' => 200,
            'user' => $this->user,
            'post_count' => $post_count,
            'action_count' => $action_count,
            'data' => $posts,
        ]);
    }


    /**
     * get Posts User
     */
    public function getPostsUser(Request $request)
    {
        $this->user = Auth::user();

        if($this->user){
            $post = Post::where('type', PostType::Post)
                ->where('user_id', $this->user->id)
                // ->with('user')
                ->with('user')
                ->with('comments')
                // ->with('postReactions')
                ->with('postReactionsWithoutRemove')
                ->orderByDesc('created_at')->paginate(20);

            $post->getCollection()->transform(function($query) {
                $images = PostMedia::where('post_id', $query->id)
                        ->with('media')
                        ->get()
                        ->map(function ($postMedia) {
                            return $postMedia->media->url_media;
                        });

                $query->images = $images;

                return $query;
            });

            return response()->json([
                'status' => 'sucess',
                'message' => 'post list user connect all plateforme',
                'code' => 200,
                'data' => $post,
            ]);
        }

    }


    /**
     * get Evennements User
     */
    public function getEvennementUsers(Request $request)
    {
        $this->user = Auth::user();
        if($this->user){

            $post = Post::where('type', PostType::Evennement)
                ->where('user_id', $this->user->id)
                    ->with('user')
                ->with('user')
                ->with('comments')
                // ->with('postReactions')
                ->with('postReactionsWithoutRemove')
                ->orderByDesc('created_at')->paginate(20);

            $post->getCollection()->transform(function($query) {
                $images = PostMedia::where('post_id', $query->id)
                        ->with('media')
                        ->get()
                        ->map(function ($postMedia) {
                            return $postMedia->media->url_media;
                        });

                $query->images = $images;

                return $query;
            });

            return response()->json([
                'status' => 'sucess',
                'message' => 'post list user connect all plateforme',
                'code' => 200,
                'data' => $post,
            ]);

        }

    }


    /**
     * Alerte Post
     */
    public function getAllAlerte(Request $request)
    {
        $post = Post::where('type', PostType::Alerte)
        ->with('user')
        ->with('comments')
        ->with('tags')
        ->with('postReactionsWithoutRemove')
        ->orderByDesc('created_at')->paginate(20);

        $post->getCollection()->transform(function($query) {
            $images = PostMedia::where('post_id', $query->id)
                    ->with('media')
                    ->get()
                    ->map(function ($postMedia) {
                        return $postMedia->media->url_media;
                    });
            // $postReactions = PostReaction::where('post_id', $query->id)
            // ->where('remove', false)->get();

            $query->images = $images;
            // $query->post_reactions = $postReactions;

            return $query;
        });


        return response()->json([
            'status' => 'sucess',
            'message' => 'Alerte',
            'code' => 200,
            'data' => $post,
        ]);
    }

    /**
     * get Alertes User
     */
    public function getAlerteUsers(Request $request)
    {
        $this->user = Auth::user();
        if($this->user){

            $post = Post::where('type', PostType::Alerte)
                ->where('user_id', $this->user->id)
                    ->with('user')
                ->with('user')
                ->with('comments')
                // ->with('postReactions')
                ->with('postReactionsWithoutRemove')
                ->orderByDesc('created_at')->paginate(20);

            $post->getCollection()->transform(function($query) {
                $images = PostMedia::where('post_id', $query->id)
                        ->with('media')
                        ->get()
                        ->map(function ($postMedia) {
                            return $postMedia->media->url_media;
                        });
                    // $postReactions = PostReaction::where('post_id', $query->id)
                    // ->where('remove', false)->get();

                    $query->images = $images;
                    // $query->post_reactions = $postReactions;

                $query->images = $images;

                return $query;
            });

            return response()->json([
                'status' => 'sucess',
                'message' => 'post list user connect all plateforme',
                'code' => 200,
                'data' => $post,
            ]);
        }

    }

    /**
     * Evennement Post
     */
    public function getAllEvennement(Request $request)
    {
        $post = Post::where('type', PostType::Evennement)
        ->with('user')
        ->with('comments')
        ->with('tags')
        ->with('postActions')
        ->with('postReactionsWithoutRemove')
        ->orderByDesc('created_at')->paginate(20);

        $post->getCollection()->transform(function($query) {
            $images = PostMedia::where('post_id', $query->id)
                    ->with('media')
                    ->get()
                    ->map(function ($postMedia) {
                        return $postMedia->media->url_media;
                    });
            // $postReactions = PostReaction::where('post_id', $query->id)
            // ->where('remove', false)->get();

            $query->images = $images;
            // $query->post_actions = $query->actions;


            return $query;
        });


        return response()->json([
            'status' => 'sucess',
            'message' => 'Evènement',
            'code' => 200,
            'data' => $post,
        ]);
    }


    /**
     *  Post Lists
     */
    public function getAllPost(Request $request)
    {
        $post = Post::where('type', PostType::Post)
        ->with('user')
        ->with('comments')
        ->with('tags')
        ->with('postReactionsWithoutRemove')
        ->orderByDesc('created_at')->paginate(20);

        $post->getCollection()->transform(function($query) {
            $images = PostMedia::where('post_id', $query->id)
                    ->with('media')
                    ->get()
                    ->map(function ($postMedia) {
                        return $postMedia->media->url_media;
                    });
            // $postReactions = PostReaction::where('post_id', $query->id)
            // ->where('remove', false)->get();

            $query->images = $images;
            // $query->post_reactions = $postReactions;

            return $query;
        });


        return response()->json([
            'status' => 'sucess',
            'message' => 'Post',
            'code' => 200,
            'data' => $post,
        ]);
    }


    /**
     *  Post Alert Evennement Lists
     */
    public function getAlertEvennement(Request $request)      
    {
        $post = Post::with('user')
        ->with('comments')
        ->with('tags')
        ->with('postActions')
        ->with('postReactionsWithoutRemove')
        ->orderByDesc('created_at')->paginate(20);

        $post->getCollection()->transform(function($query) {
            $images = PostMedia::where('post_id', $query->id)
                    ->with('media')
                    ->get()
                    ->map(function ($postMedia) {
                        return $postMedia->media->url_media;
                    });
            // $postReactions = PostReaction::where('post_id', $query->id)
            // ->where('remove', false)->get();

            $query->images = $images;
            // $query->post_reactions = $postReactions;

            return $query;
        });


        return response()->json([
            'status' => 'sucess',
            'message' => 'Post Alert Evennement',
            'code' => 200,
            'data' => $post,
        ]);
    }

    /**
     *  Post Financement Lists
     */
    public function getAllPostFinancement() {

        $post = Post::where('type', PostType::Evennement)
        ->with('user')
        ->with('comments')
        ->with('tags')
        ->with('postReactionsWithoutRemove')
        ->with(['postActions' => function (BelongsToMany $query) {
            $query->where('label', 'Financement'); 
        }])
        ->orderByDesc('created_at')->paginate(20);

        $post->getCollection()->transform(function($query) {
            $images = PostMedia::where('post_id', $query->id)
                    ->with('media')
                    ->get()
                    ->map(function ($postMedia) {
                        return $postMedia->media->url_media;
                    });

            $query->images = $images;

            return $query;
        });

        return response()->json([
            'status' => 'sucess',
            'message' => 'Post',
            'code' => 200,
            'data' => $post,
        ]);
    }

    /**
     *  Post Rechercher Lists
     */
    public function getPostSearch(Request $request) {

        $request->validate([
            'search' => 'required'
        ]);

        $users = User::where('fullname', 'like', '%'.$request->search.'%')
            ->orderByDesc('created_at')->limit(20)->get();

        $posts = Post::where('title', 'like', '%'.$request->search.'%')
            ->Orwhere('message', 'like', '%'.$request->search.'%')
            ->with('user')
            ->with('comments')
            ->with('tags')
            ->with('postReactionsWithoutRemove')
            ->with('postActions')
            ->orderByDesc('created_at')->limit(20)->get();

        // $posts->getCollection()->transform(function($query) {
        $posts->transform(function($query) {
            $images = PostMedia::where('post_id', $query->id)
                    ->with('media')
                    ->get()
                    ->map(function ($postMedia) {
                        return $postMedia->media->url_media;
                    });

            $query->images = $images;

            return $query;
        });

        return response()->json([
            'status' => 'success',
            'message' => 'Recherche',
            'code' => 200,
            'post' => $posts,
            'user' => $users,
        ]);
    }

    /**
     * Get Action
     */
    public function getAction() {
        $actions = Action::all();

        return response()->json([
            'status' => 'sucess',
            'code' => 200,
            'data' => $actions,
        ]);
    }

    /**
     * getPost
     */
    public function getPost(Request $request)
    {
        $validator = $request->validate([
            'id' => ['exists:App\Models\Post,id'],
        ]);
        $id = $request->id;

        $post = Post::where('id', $id)
            ->with('user')->with('postMedias')
            // ->with('postReactions')
            ->with('postReactionsWithoutRemove');
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
            'code' => 200,
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
        // Log::info($request);
        $post = Post::create([
            'user_id'       => $this->user->id,
            'title'     => $request->title,
            'message'       => $request->message,
            'country'       => isset($request->country)? $request->country : null,
            'city'      => isset($request->city)? $request->city : null,
            'distributed_to'        => isset($request->distributed_to)? $request->distributed_to : Distributed_to::ALL,
            'type'       => $request->type,
            'status'        => isset($request->status)? $request->status : null,
            'start_date'        => isset($request->start_date)? $request->start_date : null,
            'end_date'      => isset($request->end_date)? $request->end_date : null,
            'inscription_url'      => isset($request->inscription_url)? $request->inscription_url : null,
            'city'      => isset($request->city)? $request->city : null,
            'address'      => isset($request->address)? $request->address : null,
            

        ]);
        if ($request->tags){
            foreach ($request->tags as $label) {
                $new_tag = Tag::where('label', $label);
                if(!$new_tag){
                    $new_tag = Tag::create(['label' => $label, 'count' => 0]);
                }
                $new_tag->increment('count');
                // $new_tag->save();
            }
        }
        if($request->medias){

            foreach ($request->medias as $image) {
                $img = time() . '-' . $image->getClientOriginalName();
                $path = $image->move(public_path('post'), $img);
                $path = 'post/' . $img;
                $media = Media::create([
                    'url_media' => $path,
                ]);
                PostMedia::create([
                    'post_id' => $post->id,
                    'media_id' => $media->id,
                ]);

            }
        }
        if($request->actions){

            foreach ($request->actions as $action) {
                // $post_action = DB::table('post_actions')->insert([
                //     'post_id' => $post->id,
                //     'action_id' => intval($action),
                // ]);
                $post->actions()->attach(intval($action));
            }
        }
        return response()->json([
            'status' => 'sucess',
            'message' => 'post create',
            'code' => 200,
            'data' => $post,
        ]);

    }

    /**
     * partage post
     */
    public function sharePost(Request $request)
    {
        $validator = $request->validate([
            'post_id' => ['exists:App\Models\Post,id'],
        ]);
        $post_id = $request->post_id;
        $post = Post::where('id', $post_id)->first();

        PostShare::create([
            'post_id' => $post->id,
            'user_id' => $this->user->id,
            ]);

        $post->user->notify(new UserNotification(NotificationType::Share, 'Votre post a été partagé', $this->user->fullname, $post->id));

        return response()->json([
            'status' => 'sucess',
            'message' => 'post partage',
            'code' => 200,
            'data' => $post,
            ]);
    }

    /**
     * create action(user add action)
     */
    public function addAction(Request $request)
    {
        $validator = $request->validate([
            'user_id' => ['exists:App\Models\User,id'],
            'post_id' => ['exists:App\Models\Post,id'],
            'action_id' => ['exists:App\Models\Action,id']
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

        $postAction->post->user->notify(new UserNotification(NotificationType::Action, 'Un nouveau participant à votre événement.', $this->user->fullname, $postAction->post->id));

        return response()->json([
            'status' => 'sucess',
            'message' => 'action user ajouter avec succes',
            'code' => 200,
            'data' => $postAction,
            ]);
    }

    /**
     * create reaction post
     */
    public function addReaction(PostReactionRequest $request)
    {
        $this->user = Auth::user();
        $post = Post::where('id', $request->post_id)->first();
        if(!$post){
            return response()->json([
                'status' => 'failed',
                'message' => 'post n\'exist pas',
                'code' => '404',
                'data' => null,
                ]);
        }
        $postReaction = PostReaction::where('user_id',  $this->user->id)
            ->where('post_id', $post->id)->first();

        if($postReaction){
            if(!$postReaction->remove){
                $postReaction->update([
                    'remove' => true,
                    'reaction' => $request->reaction,
                ]);
                return response()->json([
                    'status' => 'sucess',
                    'message' => 'reaction supprimer avec succes',
                    'code' => 200,
                    'data' => $postReaction,
                ]);
            }
            else{
                $postReaction->update([
                    'remove' => false,
                    'reaction' => $request->reaction,
                ]);
                // $postReaction->post->user->notify(new UserNotification(NotificationType::Love, 'Un nouveau réaction à votre post.', $this->user->fullname, $postReaction->post->id));
                return response()->json([
                    'status' => 'sucess',
                    'message' => 'reaction ajouter avec succes',
                    'code' => 200,
                    'data' => $postReaction,
                    ]);
            }
        }
        $postReaction = PostReaction::Create([
            'user_id' => $this->user->id,
            'post_id' => $post->id,
            'reaction' => $request->reaction,
        ]);
        $postReaction->post->user->notify(new UserNotification(NotificationType::Love, 'Un nouveau réaction à votre post.', $this->user->fullname, $postReaction->post->id));

        return response()->json([
            'status' => 'sucess',
            'message' => 'reaction ajouter avec succes',
            'code' => 200,
            'data' => $postReaction,
            ]);
    }

    /**
     * delete reaction post
     */
    public function deleteReaction(Request $request)
    {
        $validator = $request->validate([
            'post_id' => ['exists:App\Models\Post,id'],
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
            'code' => 200,
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
            'distributed_to' => isset($request->distributed_to)? $request->distributed_to : null,
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
                $new_tag->increment('count');
                $new_tag->save();
            }
        }

        PostMedia::where('post_id', $post->id)->delete();

        if($request->medias){

            foreach ($request->images as $image) {
                $img = time() . $image->getClientOriginalName();
                $path = $image->move(public_path() . "\post", $img);
                $media = Media::create([
                    'url_media' => $path,
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
            'code' => 200,
            'data' => $post,
        ]);
    }

    /**
     * remove post
     */
    public function deletePost(Request $request)
    {
        $validator = $request->validate([
            'post_id' => ['exists:App\Models\Post,id'],
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
            'code' => 200,
            'data' => null,
        ]);
    }
}

