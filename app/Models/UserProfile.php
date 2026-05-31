<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    protected $fillable = [
        'user_id',
        'country',
        'state',
        'city',
        'phone',
        'phone_verified_at',
        'address',
        'postal_code',
        'occupation',
        'bio',
        'facebook_url',
        'twitter_url',
        'instagram_url',
        'linkedin_url',
    ];

    protected $casts = [
        'phone_verified_at' => 'datetime',
    ];

    // ===== RELATIONSHIPS =====

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // ===== HELPER METHODS =====

    public function getFullAddressAttribute()
    {
        $parts = array_filter([
            $this->address,
            $this->city,
            $this->state,
            $this->country,
            $this->postal_code,
        ]);

        return implode(', ', $parts);
    }

    public function isPhoneVerified()
    {
        return !is_null($this->phone_verified_at);
    }
}
