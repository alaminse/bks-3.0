<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class PackageTask extends Pivot
{
    protected $table = 'package_tasks';

    protected $fillable = [
        'package_id',
        'task_id',
        'reward_amount',
        'sort_order'
    ];

    protected $casts = [
        'reward_amount' => 'decimal:2',
    ];

    /**
     * Get the package
     */
    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    /**
     * Get the task
     */
    public function task()
    {
        return $this->belongsTo(Task::class);
    }
}
