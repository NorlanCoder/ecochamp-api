<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
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
        'active',
        'type',
        'distributed_to',
        'type_id',
        'status',
        'start_date',
        'end_date',
        'address',
        'inscription_url',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(PostType::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function postMedias(): HasMany
    {
        return $this->hasMany(PostMedia::class);
    }

    public function postShares(): HasMany
    {
        return $this->hasMany(PostShare::class);
    }

    public function postActions(): BelongsToMany
    {
        return $this->belongsToMany(Action::class, 'post_actions')
                    ->using(PostAction::class)
                    ->withPivot('id');
    }
    // public function postActions(): HasMany
    // {
    //     return $this->hasMany(PostAction::class);
    // }

    // public function actions()
    // {
    //     return $this->hasManyThrough(Action::class, PostAction::class, 'post_id', 'id', 'id', 'action_id');
    // }


    public function tags(): HasMany
    {
        return $this->hasMany(PostTag::class);
    }

    public function postReactions(): HasMany
    {
        return $this->hasMany(PostReaction::class);
    }

    public function postReactionsWithoutRemove()
    {
        return $this->postReactions()->where('remove', false);
    }
}
