<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'message',
        'country',
        'city',
        'distributed_to',
        'type_id',
        'status',
        'start_date',
        'end_date',
    ]; 

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(PostType::class);
    }

    public function postMedias(): HasMany
    {
        return $this->hasMany(PostMedia::class);
    }

    public function postShares(): HasMany
    {
        return $this->hasMany(PostShare::class);
    }

    public function tags(): HasMany
    {
        return $this->hasMany(Tag::class);
    }

    public function postReactions(): HasMany
    {
        return $this->hasMany(PostReaction::class);
    }

}
