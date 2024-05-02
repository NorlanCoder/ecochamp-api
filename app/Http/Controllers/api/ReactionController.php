<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Reaction;
use Illuminate\Http\Request;

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
}
