<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DailyProgressReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'daily_report_link_id',
        'group_id',
        'user_id',
        'reporter_name',
        'report_date',
        'title',
        'description',
        'photo_url',
        'ip_address',
        'user_agent',
        'status',
        'admin_notes',
    ];

    protected function casts(): array
    {
        return [
            'report_date' => 'date',
        ];
    }

    public function link(): BelongsTo
    {
        return $this->belongsTo(DailyReportLink::class, 'daily_report_link_id');
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

