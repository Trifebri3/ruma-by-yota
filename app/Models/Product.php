<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'group_id',
        'commodity_id',
        'name',
        'description',
        'price',
        'unit',
        'packaging_type',
        'photo_url',
        'status', // perlu_diperbaiki, dalam_evaluasi, sesuai_panduan
        'is_public',
        'qc_label_status', // sesuai, perbaikan, belum_dinilai
        'qc_packaging_status',
        'qc_info_status',
        'qc_photo_status',
        'facilitator_notes',
    ];

    protected $casts = [
        'price' => 'float',
        'is_public' => 'boolean',
    ];

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }

    public function commodity(): BelongsTo
    {
        return $this->belongsTo(Commodity::class);
    }
}
