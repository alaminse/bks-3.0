<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Withdrawal extends Model
{
    protected $fillable = [
        'user_id',
        'amount',
        'payment_method',
        'account_number',
        'account_name',
        'reference_number',
        'transaction_id',
        'status',
        'reject_reason',
        'approved_at',
        'approved_by'
    ];

    protected $casts = [
        'amount' => 'decimal:8',
        'approved_at' => 'datetime',
    ];

    /**
     * Withdrawal user
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * কে approve/reject
     */
    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Status check methods
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    /**
     * Payment method name
     */
    public function getPaymentMethodNameAttribute(): string
    {
        return strtoupper($this->payment_method);
    }

    /**
     * Scopes for easy filtering
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    public function scopeToday($query)
    {
        return $query->whereDate('created_at', today());
    }

}
