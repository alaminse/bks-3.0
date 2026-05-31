<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class FeaturedImage extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'featured_images';

    /**
     * The attributes that are mass assignable.
     *
     * এই fields গুলো mass assignment এর মাধ্যমে fill করা যাবে
     * যেমন: FeaturedImage::create([...])
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',           // Image এর title
        'description',     // Optional description
        'image_path',      // File path যেমন: storage/featured_images/banner.jpg
        'link_url',        // Optional URL যেখানে redirect করবে
        'order',           // Display order (0, 1, 2...)
        'status',          // active বা inactive
    ];

    /**
     * The attributes that should be cast.
     *
     * Database থেকে data আসলে automatically এই type এ convert হবে
     *
     * @var array<string, string>
     */
    protected $casts = [
        'order' => 'integer',           // order field integer হিসেবে return হবে
        'created_at' => 'datetime',     // Carbon instance হিসেবে return হবে
        'updated_at' => 'datetime',     // Carbon instance হিসেবে return হবে
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * JSON response এ এই fields hide করবে (যদি লাগে)
     *
     * @var array<int, string>
     */
    protected $hidden = [];

    /**
     * Get the attributes that should be appended to model arrays.
     *
     * JSON response এ extra attributes যোগ করবে
     *
     * @var array<int, string>
     */
    protected $appends = ['full_image_url'];

    /**
     * Scope a query to only include active images.
     *
     * ব্যবহার: FeaturedImage::active()->get()
     * শুধু active status এর images return করবে
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope a query to only include inactive images.
     *
     * ব্যবহার: FeaturedImage::inactive()->get()
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeInactive(Builder $query): Builder
    {
        return $query->where('status', 'inactive');
    }

    /**
     * Scope a query to order images by their display order.
     *
     * ব্যবহার: FeaturedImage::ordered()->get()
     * order column অনুযায়ী ascending order এ sort করবে
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('order', 'asc');
    }

    /**
     * Scope to get images for slider (active + ordered)
     *
     * ব্যবহার: FeaturedImage::forSlider()->get()
     * Slider এ দেখানোর জন্য ready images
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeForSlider(Builder $query): Builder
    {
        return $query->active()->ordered();
    }

    /**
     * Get the full image URL attribute.
     *
     * Accessor - automatically append হবে when model serialize হবে
     * ব্যবহার: $image->full_image_url
     *
     * @return string
     */
    public function getFullImageUrlAttribute(): string
    {
        return asset($this->image_path);
    }

    /**
     * Check if the image is active.
     *
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Check if the image is inactive.
     *
     * @return bool
     */
    public function isInactive(): bool
    {
        return $this->status === 'inactive';
    }

    /**
     * Activate the image.
     *
     * @return bool
     */
    public function activate(): bool
    {
        return $this->update(['status' => 'active']);
    }

    /**
     * Deactivate the image.
     *
     * @return bool
     */
    public function deactivate(): bool
    {
        return $this->update(['status' => 'inactive']);
    }

    /**
     * Toggle the image status.
     *
     * @return bool
     */
    public function toggleStatus(): bool
    {
        $newStatus = $this->isActive() ? 'inactive' : 'active';
        return $this->update(['status' => $newStatus]);
    }

    /**
     * Get status badge class for UI.
     *
     * @return string
     */
    public function getStatusBadgeClass(): string
    {
        return $this->isActive() ? 'bg-success' : 'bg-secondary';
    }

    /**
     * Delete the image file when model is deleted.
     *
     * Model delete হলে automatically image file ও delete হবে
     */
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($image) {
            // Delete image file if exists
            if ($image->image_path && file_exists(public_path($image->image_path))) {
                unlink(public_path($image->image_path));
            }
        });
    }
}
