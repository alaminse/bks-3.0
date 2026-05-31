<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReferralEarning extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'referral_id',
        'referrer_id',
        'referred_id',
        'type',
        'amount',
        'commission_rate',
        'status',
        'reference_type',
        'reference_id',
        'description',
        'wallet_transaction_id',
        'paid_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'commission_rate' => 'decimal:2',
        'paid_at' => 'datetime',
    ];

    // ===== RELATIONSHIPS =====

    public function referral()
    {
        return $this->belongsTo(Referral::class);
    }

    public function referrer()
    {
        return $this->belongsTo(User::class, 'referrer_id');
    }

    public function referred()
    {
        return $this->belongsTo(User::class, 'referred_id');
    }

    public function walletTransaction()
    {
        return $this->belongsTo(WalletTransaction::class);
    }

    // Polymorphic relationship for reference
    public function reference()
    {
        return $this->morphTo(__FUNCTION__, 'reference_type', 'reference_id');
    }

    // ===== SCOPES =====

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    // ===== HELPER METHODS =====

    public function approve()
    {
        $this->update(['status' => 'approved']);
    }

    public function markAsPaid()
    {
        $this->update([
            'status' => 'paid',
            'paid_at' => now(),
        ]);
    }

    public function reject()
    {
        $this->update(['status' => 'rejected']);
    }
}
