<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Land extends Model
{
    use HasFactory;

    protected $fillable = [
        'group_id',
        'name',
        'latitude',
        'longitude',
        'polygon_geojson',
        'area_m2',
        'land_status',
        'is_public',
        'manager_name',
        'commodities',
        'photo_url',
        'notes',
    ];

    protected $casts = [
        'latitude' => 'float',
        'longitude' => 'float',
        'area_m2' => 'float',
        'polygon_geojson' => 'array',
        'is_public' => 'boolean',
    ];

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }

    public function commoditiesRelation(): HasMany
    {
        return $this->hasMany(Commodity::class);
    }
}
