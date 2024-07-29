<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reviewsimage extends Model
{
    use HasFactory;

    protected $fillable = [
        'review_id',
        'name',
        'path',
    ];

    protected $hidden = [
        'review_id',
    ];
}
