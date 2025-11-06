<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Notifications\Notifiable;

class Announcement extends Model
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'title',
        'content',
        'audience',   // all, customers, technicians
        'priority',   // normal, high
        'start_date',
        'end_date',
        'status',     // Active, Scheduled, Expired, Draft
        'created_by',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date'   => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($announcement) {
            if (Auth::check()) {
                $announcement->created_by = Auth::id();
            }

            // Default status when none is given
            if (!$announcement->status) {
                $announcement->status = 'Scheduled';
            }
        });
    }

    // Automatically compute current status
    public function getStatusAttribute($value): string
    {
        if ($value && in_array($value, ['Draft', 'Archived'])) {
            return $value;
        }

        $now = now();

        if ($this->start_date && $now->lt($this->start_date)) {
            return 'Scheduled';
        }

        if ($this->end_date && $now->gt($this->end_date)) {
            return 'Expired';
        }

        return 'Active';
    }

    // Creator relationship
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Scope for active ones
    public function scopeActive($query)
    {
        return $query
            ->where(function ($q) {
                $q->whereNull('start_date')
                    ->orWhere('start_date', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('end_date')
                    ->orWhere('end_date', '>=', now());
            });
    }
}
