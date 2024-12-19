<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PostPayment extends Model
{
    use HasFactory;

    protected $fillable =
    [
        'donator_id',
        'amount',
        'post_id',
        'action',
        'donator_name',
        'mot_soutien',                
        'status'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'donator_id');
    }

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }
}
