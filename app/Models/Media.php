<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Media extends Model
{
    use HasFactory;
    protected $fillable = 
    [
        'url_media'
    ];


    public function postMedias(): HasMany
    {
        return $this->hasMany(PostMedia::class);
    }
}
