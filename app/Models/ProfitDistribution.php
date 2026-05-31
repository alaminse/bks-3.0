<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfitDistribution extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_profit_id',
        'user_id',
        'company_id',
        'share_percentage',
        'profit_amount',
        'status',
        'paid_at',
    ];

    protected $casts = [
        'share_percentage' => 'decimal:2',
        'profit_amount' => 'decimal:2',
        'paid_at' => 'datetime',
    ];

    // Relationship with company profit
    public function companyProfit()
    {
        return $this->belongsTo(CompanyProfit::class);
    }

    // Relationship with user (partner)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relationship with company
    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
