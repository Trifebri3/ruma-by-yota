<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Commodity extends Model
{
    use HasFactory;

    protected $fillable = [
        'group_id',
        'land_id',
        'name',
        'category', // Pertanian, Perkebunan, Hortikultura
        'status', // Aktif, Panen, Perawatan
        'harvest_estimate',
        'planted_area_m2',
        'photo_url',
        'notes',
    ];

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }

    public function land(): BelongsTo
    {
        return $this->belongsTo(Land::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
