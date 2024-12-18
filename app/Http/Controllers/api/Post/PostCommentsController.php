<?php

namespace App\Http\Controllers\api\Post;

use App\Enums\NotificationType;
use App\Models\Post;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Comment\CreateCommentRequest;
use App\Http\Requests\Comment\UpdateCommentRequest;
use App\Http\Requests\CommentRequest;
use App\Http\Requests\Reaction\AddCommentReactionRequest;
use App\Models\Comment;
use App\Models\CommentReaction;
use App\Notifications\UserNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class PostCommentsController extends Controller
{
    protected Post $post;
    protected Comment $comment;
    protected CommentReaction $comment_reaction;

    public function __construct(Post $post, Comment $comment, CommentReaction $comment_reaction)
    {
        $this->post = $post;
        $this->comment = $comment;
        $this->comment_reaction = $comment_reaction;
    }
    /**
     * get a comments list of a post.
     */
    public function getPostComments(Request $request)
    {
        $request->validate([
            'post_id' => ['required', 'integer', 'exists:posts,id']
        ]);

    
        $comments = Comment::where('post_id', $request->get('post_id'))->with('post:id,title')->orderBy('created_at', 'desc')->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $comments,
        ], 200);
    }

    /**
     * create a comment for a post.
     */
    public function createComment(CreateCommentRequest $request)
    {
        try {
            $post = $this->post->find($request->post_id);
            
            if (!$post) {
                return response()->json([
                    'success' => false,
                    'message' => 'Post not found',
                ], 404);
            }
        
            $comment = $post->comments()->create([
                'content' => $request->content,
                'user_id' => Auth::user()->id,
            ]);
        
            $post->user->notify(new UserNotification(
                NotificationType::Comment,
                'Un nouveau commentaire pour votre post.',
                Auth::user()->fullname,
                $request->post_id
            ));
        
            return response()->json([
                'success' => true,
                'data' => $comment,
                'message' => 'Comment created successfully',
            ], 201);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        }
        
    }

    public function updateComment(UpdateCommentRequest $request)
    {
        try {
            $comment = $this->comment->find($request->comment_id);

            if ($comment->user_id !== Auth::user()->id) {
                return response()->json(['message' => 'unauthorised']);
            }

            $comment->content = $request->content;
            $comment->save();

            return response()->json([
                'success' => true,
                'message' => 'Comment updated successfully',
                'data' => $comment,
            ], 201);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        }
    }

    /**
     * deleteComment
     */
    public function deleteComment(Request $request)
    {
        $validator = Validator::make([
            'comment_id' => ['required', 'integer', 'exists:comments,id']
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $comment = $this->comment->find($request->get('comment_id'));

        $comment->delete();
        return response()->json([
            'success' => true,
            'message' => 'Comment deleted successfully'
        ], 201);
    }

    /**
     * addCommentReaction
     */
    public function addCommentReaction(AddCommentReactionRequest $request)
    {
        try {
            $comment = $this->comment->find($request->get('comment_id'));

            $comment->reactions->create(
                [
                    'reaction_id' => $request->get('reaction_id'),
                    'user_id' => Auth::user()->id,
                    'post_id' => $comment->post_id
                ]
            );

            return response()->json([
                'success' => true,
                'message' => 'Reaction added successfully',
                'data' => $comment
            ], 201);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        }
    }
    
    /**
     * updateCommentReaction
     */
    public function updateCommentReaction(AddCommentReactionRequest $request)
    {
        try {
            $comment = $this->comment->find($request->get('comment_id'));
            if ($comment && $comment->user_id != Auth::user()->id) {
                return response()->json([
                    'message' => 'unauthorised'
                ]);
            }
            $comment->reactions->update(['reaction_id' => $request->get('reaction_id')]);

            return response()->json([
                'success' => true,
                'message' => 'Reaction updated successfully',
                'data' => $comment
            ], 201);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        }
    }

    /**
     * deleteCommentReaction
     */
    public function deleteCommentReaction(Request $request)
    {
        $validator = Validator::make([
            'comment_id' => ['required', 'integer', 'exists:comments,id']
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $comment = $this->comment->find($request->get('comment_id'));

        if ($comment && $comment->user_id != Auth::user()->id) {
            return response()->json(['message' => 'authorised']);
        }

        $this->comment_reaction->where('user_id', Auth::user()->id)->where('post_id', $comment->post_id)->delete();

        return response()->json([
            'success' => true,
            'message' => 'reaction deleted successfully'
        ], 201);
    }
}
