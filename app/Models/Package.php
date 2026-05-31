<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Package extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'daily_tasks',
        'daily_earning',
        'per_task_earning',
        'duration_days',
        'features',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'price' => 'decimal:8',
        'daily_earning' => 'decimal:8',
        'features' => 'array',
        'is_active' => 'boolean',
        'auto_verify' => 'boolean',
        'per_task_earning' => 'integer',
        'required_duration' => 'integer',
    ];

    protected static function booted()
    {
        static::creating(function ($package) {
            $package->slug = Str::slug($package->name);
        });
    }

    /**
     * এই package এর subscriptions
     */
    public function userPackages(): HasMany
    {
        return $this->hasMany(UserPackage::class);
    }

    /**
     * এই package এর tasks
     */
    public function tasks()
    {
        return $this->belongsToMany(Task::class, 'package_tasks')
            ->using(PackageTask::class)
            ->withPivot('reward_amount', 'sort_order')
            ->withTimestamps()
            ->orderBy('sort_order');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeInactive($query)
    {
        return $query->where('is_active', false);
    }

    /**
     * Active subscriptions
     */
    public function activeSubscriptions(): HasMany
    {
        return $this->hasMany(UserPackage::class)->where('status', 'active');
    }

    /**
     * Total subscribers
     */
    public function getTotalSubscribersAttribute(): int
    {
        return $this->userPackages()->distinct('user_id')->count();
    }

    /**
     * Active subscribers
     */
    public function getActiveSubscribersAttribute(): int
    {
        return $this->activeSubscriptions()->distinct('user_id')->count();
    }

    /**
     * Per task earning calculation
     */
    public function getPerTaskEarningAttribute(): float
    {
        return $this->daily_tasks > 0
            ? $this->daily_earning / $this->daily_tasks
            : 0;
    }

    /**
     * Total earning potential
     */
    public function getTotalEarningPotentialAttribute(): float
    {
        return $this->daily_earning * $this->duration_days;
    }

    /**
     * ROI Percentage
     */
    public function getRoiPercentageAttribute(): float
    {
        return $this->price > 0
            ? (($this->total_earning_potential - $this->price) / $this->price) * 100
            : 0;
    }

}
