<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyProfit extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'profit_amount',
        'profit_date',
        'profit_type',
        'description',
        'distribution_status',
        'distributed_at',
        'created_by',
    ];

    protected $casts = [
        'profit_amount' => 'decimal:2',
        'profit_date' => 'date',
        'distributed_at' => 'datetime',
    ];

    // Relationship with company
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    // Relationship with creator
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Relationship with distributions
    public function distributions()
    {
        return $this->hasMany(ProfitDistribution::class);
    }

    // Check if already distributed
    public function isDistributed()
    {
        return $this->distribution_status === 'distributed';
    }

    // Get total distributed amount
    public function getTotalDistributedAttribute()
    {
        return $this->distributions()->sum('profit_amount');
    }

    // Get number of partners who received profit
    public function getTotalPartnersAttribute()
    {
        return $this->distributions()->distinct('user_id')->count('user_id');
    }
}
