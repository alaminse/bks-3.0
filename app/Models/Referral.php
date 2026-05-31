<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Referral extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'referrer_id',
        'referred_id',
        'referral_code',
        'status',
        'activated_at',
        'completed_at',
    ];

    protected $casts = [
        'activated_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    // ===== RELATIONSHIPS =====

    public function referrer()
    {
        return $this->belongsTo(User::class, 'referrer_id');
    }

    // In User.php
    public function referredUsers()
    {
        return $this->hasMany(User::class, 'referred_by');
    }
    
    public function referred()
    {
        return $this->belongsTo(User::class, 'referred_id');
    }

    public function earnings()
    {
        return $this->hasMany(ReferralEarning::class);
    }

    // ===== SCOPES =====

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    // ===== HELPER METHODS =====

    public function activate()
    {
        $this->update([
            'status' => 'active',
            'activated_at' => now(),
        ]);
    }

    public function complete()
    {
        $this->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);
    }
}
