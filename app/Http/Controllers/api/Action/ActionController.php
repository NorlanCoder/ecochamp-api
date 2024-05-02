<?php

namespace App\Http\Controllers\api\Action;

use App\Http\Controllers\Controller;
use App\Models\Action;
use Illuminate\Http\Request;

class ActionController extends Controller
{
    /**
     * list action post.
     */
    public function listAction()
    {
        $actions = Action::all();

        return response()->json([
            'status' => 'sucess',
            'message' => 'action listes',
            'code' => '200',
            'data' => $actions,
        ]);
    }

    /**
     * create action post.
     */
    public function createPostAction(Request $request)
    {
        $validator = $request->validate([
            'label'  => ['required'],
            'value'  => ['required']
        ]);

        $action = Action::create([
            'label'  => $request->label,
            'value'  => $request->value
        ]);

        return response()->json([
            'status' => 'sucess',
            'message' => 'action create',
            'code' => '200',
            'data' => $action,
        ]);
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
     * Update action post.
     */
    public function updateAction(Request $request)
    {
        $validator = $request->validate([
            'id'  => ['exists:App\Models\Action,id'],
            'label'  => ['required'],
            'value'  => ['required']
        ]);

        $action = Action::where('id', $request->id)->first();
        if(!$action){
            return response()->json([
                'status' => 'failed',
                'message' => 'post type n\'exist pas',
                'code' => '404',
                'data' => null,
            ]); 
        }
        $action->update([
            'label'  => $request->label,
            'value'  => $request->value
        ]);

        return response()->json([
            'status' => 'sucess',
            'message' => 'action update',
            'code' => '200',
            'data' => $action,
        ]);
    }

    /**
     * Remove action poste
     */
    public function deleteAction(Request $request)
    {
        $validator = $request->validate([
            'id'  => ['required'],
        ]);

        $action = Action::where('id', $request->id)->first();
        if(!$action){
            return response()->json([
                'status' => 'failed',
                'message' => 'post type n\'exist pas',
                'code' => '404',
                'data' => null,
            ]); 
        }
        $action->delete();

        return response()->json([
            'status' => 'sucess',
            'message' => 'action delete',
            'code' => '200',
            'data' => $action,
        ]);
    }
}
