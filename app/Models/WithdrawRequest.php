<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WithdrawRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'montant',
        'user_id',
        'status',
        'phone',
    ];


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }


}
