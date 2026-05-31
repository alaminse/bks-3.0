<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WalletTransaction extends Model
{
    use HasFactory, SoftDeletes;

    const UPDATED_AT = null; // No updated_at column

    protected $fillable = [
        'wallet_id', 'type', 'amount', 'direction', 'reference_type', 'reference_id', 'description',
    ];

    // protected $casts = [
    //     'amount' => 'decimal:8',
    //     'created_at' => 'datetime',
    //     'deleted_at' => 'datetime',
    // ];

    public function wallet()
    {
        return $this->belongsTo(Wallet::class);
    }

    // Polymorphic relation
    public function reference()
    {
        return $this->morphTo();
    }

    // Scopes
    public function scopeCredits($query)
    {
        return $query->where('direction', 'credit');
    }

    public function scopeDebits($query)
    {
        return $query->where('direction', 'debit');
    }

    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeBetweenDates($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }

    // Helper methods
    public function isCredit()
    {
        return $this->direction === 'credit';
    }

    public function isDebit()
    {
        return $this->direction === 'debit';
    }

    public function getFormattedAmountAttribute()
    {
        return number_format($this->amount, 2);
    }

    public function getSignedAmountAttribute()
    {
        return $this->isCredit() ? '+' . $this->formatted_amount : '-' . $this->formatted_amount;
    }
}
