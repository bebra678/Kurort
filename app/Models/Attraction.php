<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attraction extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'titleDesc',
        'city_id',
        'weekWork',
        'type_id',
        'isTop',
        'description',
        'previewDescription',
        'percent',
        'preview',
        'text',
        'kitchen',
        'forChildren',
        'address',
        'latitude',
        'longitude',
        'phone',
        'social',
        'verified',
        'reasonsVisit',
        'chooseCurort26',
        'maxCheck',
        'minCheck',
        'features',
    ];

    protected $casts = [
        'reasonsVisit' => 'array',
        'kitchen' => 'array',
        'forChildren' => 'array',
        'social' => 'array',
        'verified' => 'array',
        'weekWork' => 'array',
    ];
    public function city(): BelongsTo
    {
        return $this->belongsTo(Citie::class);
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(Type::class);
    }
}
