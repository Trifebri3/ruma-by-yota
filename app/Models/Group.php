<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Group extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'category',
        'regency',
        'district',
        'village',
        'commodity_summary',
        'status', // pending, active, rejected
        'is_public',
        'business_name',
        'business_logo_url',
        'pic_name',
        'pic_public_visible',
        'public_description',
        'leader_name',
        'contact_phone',
        'address',
        'logo_url',
        'banner_url',
        'facilitator_id',
        'program_id',
        'verification_notes',
        'verified_at',
    ];

    protected $casts = [
        'verified_at' => 'datetime',
        'is_public' => 'boolean',
        'pic_public_visible' => 'boolean',
    ];

    public function members(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function facilitator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'facilitator_id');
    }

    public function program(): BelongsTo
    {
        return $this->belongsTo(Program::class);
    }

    public function lands(): HasMany
    {
        return $this->hasMany(Land::class);
    }

    public function commodities(): HasMany
    {
        return $this->hasMany(Commodity::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function financialRecords(): HasMany
    {
        return $this->hasMany(FinancialRecord::class);
    }

    public function marketingSubmissions(): HasMany
    {
        return $this->hasMany(MarketingSubmission::class);
    }

    public function mentoringNotes(): HasMany
    {
        return $this->hasMany(MentoringNote::class);
    }

    public function dailyReportLinks(): HasMany
    {
        return $this->hasMany(DailyReportLink::class);
    }

    public function dailyProgressReports(): HasMany
    {
        return $this->hasMany(DailyProgressReport::class);
    }

    public function getTotalBalanceAttribute(): float
    {
        $income = $this->financialRecords()->where('type', 'masuk')->sum('amount');
        $expense = $this->financialRecords()->where('type', 'keluar')->sum('amount');

        return (float) ($income - $expense);
    }

    public function getTotalIncomeAttribute(): float
    {
        return (float) $this->financialRecords()->where('type', 'masuk')->sum('amount');
    }

    public function getTotalExpenseAttribute(): float
    {
        return (float) $this->financialRecords()->where('type', 'keluar')->sum('amount');
    }
}
