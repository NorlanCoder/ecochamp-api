<?php

namespace App\Http\Controllers\Api\Post;

use App\Http\Controllers\Controller;
use App\Models\PostType;
use Illuminate\Http\Request;

class PostTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * create type post.
     */
    public function createPostType(Request $request)
    {
        $validator = $request->validate([
            'label'  => ['required'],
            'value'  => ['required']
        ]);

        $postType = PostType::create([
            'label'  => $request->label,
            'value'  => $request->value
        ]);

        return response()->json([
            'status' => 'sucess',
            'message' => 'postType create',
            'code' => '200',
            'data' => $postType,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update type post.
     */
    public function updatePostType(Request $request)
    {
        $validator = $request->validate([
            'id'  => ['required'],
            'label'  => ['required'],
            'value'  => ['required']
        ]);

        $postType = PostType::where('id', $request->id)->first();
        if(!$postType){
            return response()->json([
                'status' => 'failed',
                'message' => 'post type n\'exist pas',
                'code' => '404',
                'data' => null,
            ]); 
        }
        $postType->update([
            'label'  => $request->label,
            'value'  => $request->value
        ]);

        return response()->json([
            'status' => 'sucess',
            'message' => 'postType update',
            'code' => '200',
            'data' => $postType,
        ]);
    }

    /**
     * Remove type poste
     */
    public function deletePostType(Request $request)
    {
        $validator = $request->validate([
            'id'  => ['required'],
        ]);

        $postType = PostType::where('id', $request->id)->first();
        if(!$postType){
            return response()->json([
                'status' => 'failed',
                'message' => 'post type n\'exist pas',
                'code' => '404',
                'data' => null,
            ]); 
        }
        $postType->delete();

        return response()->json([
            'status' => 'sucess',
            'message' => 'postType delete',
            'code' => '200',
            'data' => $postType,
        ]);
    }
}
