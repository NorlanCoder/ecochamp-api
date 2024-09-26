<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PostMedia extends Model
{
    use HasFactory;

    protected $fillable = 
    [
        'post_id',
        'media_id',
        'remove'
    ];


    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    public function media(): BelongsTo
    {
        return $this->belongsTo(Media::class);
    }

    public function getUrlMediaAttribute()
    {
        return $this->media ? $this->media->url_media : null;
    }

}
