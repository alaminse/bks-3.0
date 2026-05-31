<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReferralSetting extends Model
{
    protected $fillable = [
        'generation',
        'commission_rate',
        'is_active',
        'label',
    ];

    protected $casts = [
        'is_active'       => 'boolean',
        'commission_rate' => 'decimal:2',
        'generation'      => 'integer',
    ];

    // ─── Scopes ───────────────────────────────────────────────────────────

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // ─── Helpers ──────────────────────────────────────────────────────────

    /**
     * Get max active generation number
     */
    public static function maxActiveGeneration(): int
    {
        return static::active()->max('generation') ?? 1;
    }

    /**
     * Get commission rate for a specific generation (returns 0 if not active)
     */
    public static function rateForGeneration(int $generation): float
    {
        $setting = static::where('generation', $generation)
            ->where('is_active', true)
            ->first();

        return $setting ? (float) $setting->commission_rate : 0.0;
    }

    /**
     * All active settings keyed by generation number
     */
    public static function activeMap(): array
    {
        return static::active()
            ->orderBy('generation')
            ->get()
            ->keyBy('generation')
            ->toArray();
    }
}
