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
        return response()->json([
            'success' => true,
            'data' => $this->reaction
        ]);
    }
}
