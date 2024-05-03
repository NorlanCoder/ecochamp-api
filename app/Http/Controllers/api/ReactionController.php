<?php

namespace App\Http\Controllers\api;

use App\Models\Reaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

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
        return response()->json([
            'success' => true,
            'data' => $this->reaction
        ], 200);
    }

    public function addReaction(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'label' => ['required', 'string', 'max:255'],
            'value' => ['required', 'string', 'max:255']
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $reaction = $this->reaction->create([
            'label' => $request->label,
            'value' => $request->value
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Reaction added succesfully',
            'data' => $reaction
        ]);
    }

    public function updateReaction(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'reaction_id' => ['required', 'integer', 'exists:reactions,id'],
            'label' => ['required', 'string', 'max:255'],
            'value' => ['required', 'string', 'max:255']
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $reaction = $this->reaction->update([
            'label' => $request->label,
            'value' => $request->value
        ]);

        return response()->json([
            'success' => true,
            'message' => 'reaction updated successfully',
            'data' => $reaction
        ]);
    }
}
