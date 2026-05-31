<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SharePriceHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'old_price',
        'new_price',
        'change_percentage',
        'reason',
        'changed_by',
    ];

    protected $casts = [
        'old_price' => 'decimal:2',
        'new_price' => 'decimal:2',
        'change_percentage' => 'decimal:2',
    ];

    // Relationship with company
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    // Relationship with admin who changed
    public function changedBy()
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}
