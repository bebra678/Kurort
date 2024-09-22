<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Citie extends Model
{
    use HasFactory;

    protected $fillable = [
        'name'
    ];

//    public function Food(): HasMany
//    {
//        return $this->hasMany(Food::class);
//    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(self::class);
    }
}
