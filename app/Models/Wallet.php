<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Wallet extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'currency',
        'balance',
        'locked_balance'
    ];

    protected $casts = [
        'balance' => 'decimal:8',
        'locked_balance' => 'decimal:8',
    ];

    /**
     * Wallet User
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * all transactions
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(WalletTransaction::class);
    }

    /**
     * Available balance
     */
    public function getAvailableBalanceAttribute(): float
    {
        return $this->balance - $this->locked_balance;
    }

    /**
     * Sufficient Balance check
     */
    public function hasSufficientBalance(float $amount): bool
    {
        return $this->getAvailableBalanceAttribute() >= $amount;
    }
}
