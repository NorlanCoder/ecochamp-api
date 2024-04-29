<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PostActionUser extends Model
{
    use HasFactory;
    protected $fillable =
    [
        'post_action_id',
        'user_id'
    ];

    public function post_action(): BelongsTo
    {
        return $this->belongsTo(PostAction::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
