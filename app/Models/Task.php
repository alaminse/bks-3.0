<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'task_type',
        'task_url',
        'adsterra_ad_code',
        'ad_skip_delay',
        'auto_verify',
        'required_duration',
        'estimated_time',
        'status',
    ];

    protected $casts = [
        'auto_verify'  => 'boolean',
        'ad_skip_delay' => 'integer',
    ];

    // ─── Scopes ──────────────────────────────────────────────
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeInactive($query)
    {
        return $query->where('status', 'inactive');
    }

    // ─── Relationships ────────────────────────────────────────
    public function packages()
    {
        return $this->belongsToMany(Package::class, 'package_tasks')
            ->withPivot('reward_amount', 'sort_order')
            ->withTimestamps();
    }

    public function submissions()
    {
        return $this->hasMany(UserTaskSubmission::class);
    }

    // ─── Accessors ────────────────────────────────────────────

    /**
     * Badge color per task type
     */
    public function getTypeBadgeColorAttribute(): string
    {
        return match ($this->task_type) {
            'youtube'  => 'danger',
            'visit'    => 'primary',
            'adsterra' => 'warning',
            default    => 'secondary',
        };
    }

    /**
     * Icon class per task type
     */
    public function getTypeIconAttribute(): string
    {
        return match ($this->task_type) {
            'youtube'  => 'bi bi-youtube',
            'visit'    => 'bi bi-globe',
            'adsterra' => 'bi bi-megaphone-fill',
            default    => 'bi bi-check-circle',
        };
    }

    /**
     * Is this an adsterra ad task?
     */
    public function getIsAdsterraAttribute(): bool
    {
        return $this->task_type === 'adsterra';
    }

    /**
     * Effective skip delay (fallback to required_duration)
     */
    public function getEffectiveSkipDelayAttribute(): int
    {
        return $this->ad_skip_delay ?? $this->required_duration ?? 5;
    }


    public function scopeByType($query, $type)
    {
        return $query->where('task_type', $type);
    }
}
