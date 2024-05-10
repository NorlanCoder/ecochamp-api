<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Message extends Model
{
    use HasFactory;
    protected $fillable =
    [
        'from_id',
        'to_id',
        'content'
    ];

    public function from(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
