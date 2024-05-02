<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Follow extends Model
{
    use HasFactory;
    protected $fillable = 
    [
        'follower_user_id',
        'followed_user_id',
        'remove'
    ];

    public function follower_user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function followed_user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

}
