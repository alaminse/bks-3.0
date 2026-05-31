<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserTaskSubmission extends Model
{
    /**
     * Table name
     */
    protected $table = 'user_task_submissions';

    /**
     * Disable default timestamps (we use custom submitted_at)
     */
    public $timestamps = false;

    /**
     * Fillable fields
     */
    protected $fillable = [
        'user_id',
        'user_package_id',
        'task_id',
        'proof',
        'proof_text',
        'status',
        'reward_amount',
        'rejection_reason',
        'submitted_at',
        'approved_at',
        'approved_by'
    ];

    /**
     * Cast attributes
     */
    protected $casts = [
        'submitted_at' => 'datetime',
        'approved_at' => 'datetime',
        'reward_amount' => 'decimal:2',
    ];

    /**
     * Default attributes
     */
    protected $attributes = [
        'status' => 'pending',
    ];

    /**
     * ========================================
     * RELATIONSHIPS
     * ========================================
     */

    /**
     * User who submitted the task
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * User's active package
     */
    public function userPackage(): BelongsTo
    {
        return $this->belongsTo(UserPackage::class);
    }

    /**
     * The task that was submitted
     */
    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

    /**
     * Admin who approved/rejected
     */
    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * ========================================
     * SCOPES
     * ========================================
     */

    /**
     * Scope: Get pending submissions
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope: Get approved submissions
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope: Get rejected submissions
     */
    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    /**
     * Scope: Get today's submissions
     */
    public function scopeToday($query)
    {
        return $query->whereDate('submitted_at', today());
    }

    /**
     * Scope: Get submissions for specific date
     */
    public function scopeForDate($query, $date)
    {
        return $query->whereDate('submitted_at', $date);
    }

    /**
     * Scope: Get submissions by user
     */
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * ========================================
     * STATUS CHECKS
     * ========================================
     */

    /**
     * Check if submission is pending
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if submission is approved
     */
    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    /**
     * Check if submission is rejected
     */
    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    /**
     * ========================================
     * ACCESSORS & MUTATORS
     * ========================================
     */

    /**
     * Get status badge color
     */
    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'pending' => 'warning',
            'approved' => 'success',
            'rejected' => 'danger',
            default => 'secondary'
        };
    }

    /**
     * Get status icon
     */
    public function getStatusIconAttribute(): string
    {
        return match($this->status) {
            'pending' => 'bi-clock-history',
            'approved' => 'bi-check-circle-fill',
            'rejected' => 'bi-x-circle-fill',
            default => 'bi-circle'
        };
    }

    /**
     * Get formatted reward amount
     */
    public function getFormattedRewardAttribute(): string
    {
        return '$' . number_format($this->reward_amount, 2);
    }

    /**
     * Get proof URL
     */
    public function getProofUrlAttribute(): ?string
    {
        return $this->proof ? asset('storage/' . $this->proof) : null;
    }

    /**
     * Check if proof exists
     */
    public function hasProof(): bool
    {
        return !empty($this->proof);
    }

    /**
     * Get time elapsed since submission
     */
    public function getTimeElapsedAttribute(): string
    {
        return $this->submitted_at->diffForHumans();
    }

    /**
     * ========================================
     * HELPER METHODS
     * ========================================
     */

    /**
     * Mark as approved
     */
    public function markAsApproved(int $adminId): bool
    {
        return $this->update([
            'status' => 'approved',
            'approved_at' => now(),
            'approved_by' => $adminId,
        ]);
    }

    /**
     * Mark as rejected
     */
    public function markAsRejected(string $reason, int $adminId): bool
    {
        return $this->update([
            'status' => 'rejected',
            'rejection_reason' => $reason,
            'approved_at' => now(),
            'approved_by' => $adminId,
        ]);
    }

    /**
     * Check if can be approved/rejected
     */
    public function canBeProcessed(): bool
    {
        return $this->status === 'pending';
    }
}
