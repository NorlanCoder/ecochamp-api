<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    
}
