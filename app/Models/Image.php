<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    public function categorie(): BelongsTo
    {
        return $this->belongsTo(Categorie::class, 'category_id');
    }
}
