<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MarketingSubmission extends Model
{
    use HasFactory;

    protected $fillable = [
        'task_id',
        'group_id',
        'photo_url',
        'caption',
        'notes',
        'status', // belum_dikerjakan, dikumpulkan, perlu_revisi, disetujui, dipublikasikan
        'facilitator_feedback',
        'submitted_at',
        'reviewed_at',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
        'reviewed_at' => 'datetime',
    ];

    public function task(): BelongsTo
    {
        return $this->belongsTo(MarketingTask::class, 'task_id');
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }
}
