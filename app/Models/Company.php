<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'total_value',
        'total_shares_issued',
        'available_shares',
        'share_price',
        'logo',
        'status',
    ];

    protected $casts = [
        'total_value' => 'decimal:2',
        'total_shares_issued' => 'decimal:2',
        'available_shares' => 'decimal:2',
        'share_price' => 'decimal:2',
    ];

    // Relationship with partner shares
    public function partnerShares()
    {
        return $this->hasMany(PartnerShare::class);
    }

    // Get total invested amount
    public function getTotalInvestedAttribute()
    {
        return $this->partnerShares()->sum('invested_amount');
    }

    // Get total partners count
    public function getTotalPartnersAttribute()
    {
        return $this->partnerShares()->distinct('user_id')->count('user_id');
    }

    /**
     * Update share price and recalculate all shares
     */
    public function updateSharePrice($newSharePrice)
    {
        DB::beginTransaction();

        try {
            $oldSharePrice = $this->share_price;

            // Update share price
            $this->share_price = $newSharePrice;

            // Recalculate available shares based on new price
            $totalValueAvailable = $this->available_shares * $oldSharePrice;
            $this->available_shares = $totalValueAvailable / $newSharePrice;

            $this->save();

            DB::commit();
            return true;

        } catch (\Exception $e) {
            DB::rollBack();
            return false;
        }
    }
}
