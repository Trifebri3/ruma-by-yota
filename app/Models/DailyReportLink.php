<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class DailyReportLink extends Model
{
    use HasFactory;

    protected $fillable = [
        'group_id',
        'title',
        'description',
        'token',
        'daily_limit',
        'is_active',
        'start_date',
        'end_date',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'daily_limit' => 'integer',
            'start_date' => 'date',
            'end_date' => 'date',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function ($model) {
            if (empty($model->token)) {
                $model->token = Str::random(32);
            }
        });
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function reports(): HasMany
    {
        return $this->hasMany(DailyProgressReport::class);
    }

    /**
     * Hitung jumlah laporan hari ini berdasarkan zona WIB (Asia/Jakarta)
     */
    public function todayReportsCount(?string $date = null): int
    {
        $targetDate = $date ?? Carbon::now('Asia/Jakarta')->toDateString();

        return $this->reports()
            ->whereDate('report_date', $targetDate)
            ->count();
    }

    /**
     * Cek apakah kuota hari ini sudah terpenuhi
     */
    public function hasReachedDailyLimit(?string $date = null): bool
    {
        if ($this->daily_limit <= 0) {
            return false; // 0 = tidak dibatasi
        }

        return $this->todayReportsCount($date) >= $this->daily_limit;
    }

    /**
     * Sisa kuota hari ini (null jika unlimited)
     */
    public function remainingDailyQuota(?string $date = null): ?int
    {
        if ($this->daily_limit <= 0) {
            return null; // Unlimited
        }

        $count = $this->todayReportsCount($date);
        $rem = $this->daily_limit - $count;

        return max(0, $rem);
    }

    /**
     * Cek apakah tautan saat ini aktif dan dalam rentang tanggal
     */
    public function isValidNow(): bool
    {
        if (! $this->is_active) {
            return false;
        }

        $today = Carbon::now('Asia/Jakarta')->toDateString();

        if ($this->start_date && $today < $this->start_date->toDateString()) {
            return false;
        }

        if ($this->end_date && $today > $this->end_date->toDateString()) {
            return false;
        }

        return true;
    }

    /**
     * URL publik untuk pengisian laporan
     */
    public function getPublicUrlAttribute(): string
    {
        return route('public.report.show', $this->token);
    }
}

