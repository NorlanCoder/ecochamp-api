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
        'chat_id',
        'from_id',
        'to_id',
        'read_at',
        'is_delete',
        'content'
    ];

    public function from(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function to(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
