<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Router extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'titleDesc',
        'city_id',
        'restrictions',
        'type_id',
        'isTop',
        'description',
        'previewDescription',
        'percent',
        'preview',
        'text',
        'nameInstitution',
        'addressesTicet',
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
        'site',
    ];

    protected $casts = [
        'reasonsVisit' => 'array',
        'social' => 'array',
        'verified' => 'array',
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
