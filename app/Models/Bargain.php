<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bargain extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
        'offered_price',
        'status',
    ];

    protected $table = 'bargain';
}
