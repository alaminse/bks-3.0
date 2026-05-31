<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PartnerShare extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'company_id',
        'invested_amount',
        'share_quantity',
        'share_percentage',
        'purchase_date',
        'status',
        'sold_price',
        'sold_at',
        'transferred_to',
        'transferred_from',
        'transferred_at',
    ];

    protected $casts = [
        'invested_amount' => 'decimal:2',
        'share_quantity' => 'decimal:2',
        'share_percentage' => 'decimal:2',
        'sold_price' => 'decimal:2',
        'purchase_date' => 'date',
        'sold_at' => 'datetime',
        'transferred_at' => 'datetime',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function transferredToUser()
    {
        return $this->belongsTo(User::class, 'transferred_to');
    }

    public function transferredFromUser()
    {
        return $this->belongsTo(User::class, 'transferred_from');
    }

    // Calculate current market value
    public function getCurrentValueAttribute()
    {
        return $this->share_quantity * $this->company->share_price;
    }

    // Calculate profit/loss if sold
    public function getProfitLossAttribute()
    {
        if ($this->status == 'sold' && $this->sold_price) {
            return $this->sold_price - $this->invested_amount;
        }
        return 0;
    }

    // Calculate profit/loss percentage if sold
    public function getProfitLossPercentageAttribute()
    {
        if ($this->status == 'sold' && $this->sold_price && $this->invested_amount > 0) {
            return (($this->sold_price - $this->invested_amount) / $this->invested_amount) * 100;
        }
        return 0;
    }

    // Calculate unrealized profit/loss
    public function getUnrealizedProfitLossAttribute()
    {
        if ($this->status == 'active') {
            return $this->current_value - $this->invested_amount;
        }
        return 0;
    }

    // Calculate unrealized profit/loss percentage
    public function getUnrealizedProfitLossPercentageAttribute()
    {
        if ($this->status == 'active' && $this->invested_amount > 0) {
            return (($this->current_value - $this->invested_amount) / $this->invested_amount) * 100;
        }
        return 0;
    }
}
