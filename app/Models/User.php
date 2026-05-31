<?php

namespace App\Models;

use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, HasRoles, SoftDeletes;

    protected $fillable = [
        'name',
        'last_name',
        'email',
        'password',
        'avatar',
        'date_of_birth',
        'gender',
        'status',
        'last_login_ip',
        'referred_by',
        'total_referrals',
        'total_referral_earnings',
        'isDemo'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'last_login_ip' => 'string',
            'total_referrals' => 'integer',
            'total_referral_earnings' => 'decimal:2',
            'date_of_birth' => 'date',
            'isDemo' => 'boolean',
        ];
    }

    // Auto-generate referral code when creating user
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            if (empty($user->referral_code)) {
                $user->referral_code = self::generateUniqueReferralCode();
            }
        });
    }

    // ===== RELATIONSHIPS =====

    public function profile()
    {
        return $this->hasOne(UserProfile::class);
    }

    public function referrals()
    {
        return $this->hasMany(Referral::class, 'referrer_id');
    }

    public function referrer()
    {
        return $this->belongsTo(User::class, 'referred_by');
    }

    public function referralEarnings()
    {
        return $this->hasMany(ReferralEarning::class, 'referrer_id');
    }

    public function referredUsers()
    {
        return $this->hasMany(User::class, 'referred_by');
    }

    public function walletTransactions()
    {
        return $this->hasMany(WalletTransaction::class);
    }

    public function wallet()
    {
        return $this->hasOne(Wallet::class);
    }

    public function partnerShares()
    {
        return $this->hasMany(PartnerShare::class);
    }

    public function deposits()
    {
        return $this->hasMany(Deposit::class);
    }

    public function withdrawals()
    {
        return $this->hasMany(Withdrawal::class);
    }

    public function userPackages()
    {
        return $this->hasMany(UserPackage::class);
    }

    public function activePackages()
    {
        return $this->hasMany(UserPackage::class)
            ->where('status', 'active')
            ->where('valid_until', '>', now());
    }

    public function taskSubmissions()
    {
        return $this->hasMany(UserTaskSubmission::class);
    }

    // ===== HELPER METHODS =====

    public function getFullNameAttribute()
    {
        return trim($this->name . ' ' . $this->last_name);
    }

    public function getAvatarUrlAttribute()
    {
        if ($this->avatar) {
            return asset('storage/' . $this->avatar);
        }

        return 'https://ui-avatars.com/api/?name=' . urlencode($this->full_name) . '&size=200&background=4F46E5&color=fff';
    }

    public function getAgeAttribute()
    {
        if (!$this->date_of_birth) {
            return null;
        }

        return $this->date_of_birth->age;
    }

    public function getWalletBalanceAttribute()
    {
        return $this->wallet
            ? (float) str_replace(',', '', number_format($this->wallet->balance, 2))
            : 0;
    }

    public static function generateUniqueReferralCode()
    {
        do {
            $code = strtoupper(Str::random(8));
        } while (self::where('referral_code', $code)->exists());

        return $code;
    }

    public function getReferralLink()
    {
        return url('/register?ref=' . $this->referral_code);
    }

    public function getTotalApprovedEarnings()
    {
        return $this->referralEarnings()
            ->where('status', 'approved')
            ->sum('amount');
    }

    public function getPendingEarnings()
    {
        return $this->referralEarnings()
            ->where('status', 'pending')
            ->sum('amount');
    }

    public function getActiveReferralsCount()
    {
        return $this->referrals()
            ->where('status', 'active')
            ->count();
    }

    public function incrementReferralCount()
    {
        $this->increment('total_referrals');
    }

    public function addToReferralEarnings($amount)
    {
        $this->increment('total_referral_earnings', $amount);
    }
}
