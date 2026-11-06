<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'group_id',
        'title',
        'event_type', // pelatihan, zoom, mentoring, kunjungan, deadline, evaluasi
        'event_date',
        'time_info',
        'location_or_link',
        'description',
        'badge_month',
        'badge_day',
        'status',
    ];

    protected $casts = [
        'event_date' => 'datetime',
    ];

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }
}
