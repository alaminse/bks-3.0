<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DailyPackageEarning extends Model
{
    protected $fillable = [
        'user_package_id',
        'earning_date',
        'total_earned',
        'task_count'
    ];

    protected $casts = [
        'earning_date' => 'date',
        'total_earned' => 'decimal:2',
    ];


    public function userPackage()
    {
        return $this->belongsTo(UserPackage::class);
    }

    /**
     * Check if daily limit reached
     */
    public function hasReachedLimit(): bool
    {
        $package = $this->userPackage->package;
        return $this->total_earned >= $package->daily_earning;
    }

    /**
     * Check if task limit reached
     */
    public function hasReachedTaskLimit(): bool
    {
        $package = $this->userPackage->package;
        return $this->task_count >= $package->daily_tasks;
    }

    /**
     * Get remaining earning capacity
     */
    public function getRemainingEarningAttribute(): float
    {
        $package = $this->userPackage->package;
        return max(0, $package->daily_earning - $this->total_earned);
    }

    /**
     * Get remaining task count
     */
    public function getRemainingTasksAttribute(): int
    {
        $package = $this->userPackage->package;
        return max(0, $package->daily_tasks - $this->task_count);
    }
}
