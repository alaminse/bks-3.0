<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContactMessage extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'contact_messages';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'message',
        'is_read',
        'read_at',
        'read_by',
        'reply_message',
        'replied_at',
        'replied_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'is_read' => 'boolean',
        'read_at' => 'datetime',
        'replied_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Get the user who read this message.
     */
    public function readBy()
    {
        return $this->belongsTo(User::class, 'read_by');
    }

    /**
     * Get the user associated with this email (if exists).
     * This is optional - contact messages can be from non-registered users.
     */
    public function user()
    {
        return $this->hasOne(User::class, 'email', 'email');
    }

    /**
     * Get the user who replied to this message.
     */
    public function repliedBy()
    {
        return $this->belongsTo(User::class, 'replied_by');
    }

    /**
     * Scope a query to only include unread messages.
     */
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    /**
     * Scope a query to only include read messages.
     */
    public function scopeRead($query)
    {
        return $query->where('is_read', true);
    }

    /**
     * Scope a query to only include replied messages.
     */
    public function scopeReplied($query)
    {
        return $query->whereNotNull('replied_at');
    }

    /**
     * Scope a query to only include pending messages (not replied).
     */
    public function scopePending($query)
    {
        return $query->whereNull('replied_at');
    }

    /**
     * Get status badge class
     */
    public function getStatusBadgeAttribute()
    {
        if ($this->replied_at) {
            return 'success';
        } elseif ($this->is_read) {
            return 'info';
        } else {
            return 'warning';
        }
    }

    /**
     * Get status text
     */
    public function getStatusTextAttribute()
    {
        if ($this->replied_at) {
            return 'Replied';
        } elseif ($this->is_read) {
            return 'Read';
        } else {
            return 'Unread';
        }
    }
}
