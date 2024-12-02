<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Action extends Model
{
    use HasFactory;
    protected $fillable =
    [
        'label',
        'value'
    ];

    public function posts(): BelongsToMany
    {
        return $this->belongsToMany(Post::class,'post_actions')->using(PostAction::class)->withPivot('id');;
    }
}
