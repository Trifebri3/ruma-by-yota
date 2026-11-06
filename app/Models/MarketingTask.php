<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MarketingTask extends Model
{
    use HasFactory;

    protected $fillable = [
        'facilitator_id',
        'title',
        'description',
        'instructions',
        'reference_pdf_name',
        'reference_pdf_url',
        'reference_image_url',
        'deadline',
        'priority',
        'is_active',
    ];

    protected $casts = [
        'deadline' => 'datetime',
        'instructions' => 'array',
        'is_active' => 'boolean',
    ];

    public function facilitator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'facilitator_id');
    }

    public function submissions(): HasMany
    {
        return $this->hasMany(MarketingSubmission::class, 'task_id');
    }
}
