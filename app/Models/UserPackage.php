<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class UserPackage extends Model
{
    protected $fillable = [
        'user_id',
        'package_id',
        'purchase_price',
        'daily_task_limit',
        'daily_earning_limit',
        'total_earning',
        'completed_tasks',
        'status',
        'valid_until'
    ];

    protected $casts = [
        'purchase_price' => 'decimal:8',
        'daily_earning_limit' => 'decimal:8',
        'total_earning' => 'decimal:8',
        'valid_until' => 'datetime',
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    public function dailyEarnings()
    {
        return $this->hasMany(DailyPackageEarning::class);
    }

    public function todaySubmissions(): HasMany
    {
        return $this->hasMany(UserTaskSubmission::class, 'user_package_id', 'id')
            ->where('status', 'approved')
            ->whereDate('submitted_at', today());
    }

    public function getTodayTaskCountAttribute(): int
    {
        if ($this->relationLoaded('todaySubmissions')) {
            return $this->todaySubmissions->count();
        }

        return $this->todaySubmissions()->count();
    }

    public function getTodayEarningAttribute(): float
    {
        if ($this->relationLoaded('todaySubmissions')) {
            return (float) $this->todaySubmissions->sum('reward_amount');
        }

        return (float) $this->todaySubmissions()->sum('reward_amount');
    }

    public function hasTodayTaskLimitReached(): bool
    {
        return $this->today_task_count >= $this->daily_task_limit;
    }

    public function hasTodayEarningLimitReached(): bool
    {
        return $this->today_earning >= $this->daily_earning_limit;
    }

    public function isActive(): bool
    {
        return $this->status === 'active' && $this->valid_until > now();
    }

    public function isExpired(): bool
    {
        return $this->status === 'expired' || $this->valid_until <= now();
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    public function getDaysRemainingAttribute(): int
    {
        return $this->valid_until ? max(0, now()->diffInDays($this->valid_until, false)) : 0;
    }

    public function getProgressPercentageAttribute(): float
    {
        $totalPossibleEarning = $this->daily_earning_limit * $this->package->duration_days;
        return $totalPossibleEarning > 0
            ? ($this->total_earning / $totalPossibleEarning) * 100
            : 0;
    }

    public function getRoiAchievedAttribute(): float
    {
        return $this->purchase_price > 0
            ? (($this->total_earning - $this->purchase_price) / $this->purchase_price) * 100
            : 0;
    }

    public function todayEarning()
    {
        return $this->hasOne(DailyPackageEarning::class)
            ->where('earning_date', today());
    }
}
