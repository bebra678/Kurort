<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Routerpoint extends Model
{
    use HasFactory;

    protected $hidden = [
        'id',
        'router_id',
    ];
}
