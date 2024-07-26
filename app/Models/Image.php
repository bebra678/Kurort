<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'page',
        'card_id',
        'category_id',
    ];

    protected $hidden = [
        'card_id',
        'page',
        'category_id',
    ];
}
