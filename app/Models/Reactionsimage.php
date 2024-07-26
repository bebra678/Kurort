<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reactionsimage extends Model
{
    use HasFactory;

    protected $fillable = [
        'img_id',
        'type',
        'user_id',
    ];
}
