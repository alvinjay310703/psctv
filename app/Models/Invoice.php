<?php
namespace App\Models;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = [
    'subscription_id',
    'customer_id',    // NEW for manual bills
    'customer_name',   // ✅ new for manual walk-in
    'invoice_no',
    'description',
    'amount_due',
    'amount_paid',
    'due_date',
    'billing_date',
    'status',
    'is_recurring',
    'notes',
];


    protected static function boot()
{
    parent::boot();

    static::creating(function ($invoice) {
        if (empty($invoice->invoice_no)) {
            $invoice->invoice_no = 'INV-' . str_pad((Invoice::max('id') + 1), 6, '0', STR_PAD_LEFT);
        }
    });
}

    protected $casts = [
        'due_date'     => 'date',
        'billing_date' => 'date',
        'is_recurring' => 'boolean',
    ];

   // --- Relationships ---
public function subscription()
{
    return $this->belongsTo(Subscription::class);
}

public function customer()
{
    return $this->belongsTo(Customer::class);
}

public function payments()
{
    return $this->hasMany(Payment::class);
}

// --- Helpers ---
public function balanceRemaining(): float
{
    return max(0, $this->amount_due - $this->amount_paid);
}

public function isOverdue(): bool
{
    return $this->status !== 'paid' && $this->due_date?->isPast();
}

public function markOverdue()
{
    if ($this->isOverdue()) {
        $this->updateQuietly(['status' => 'overdue']);
    }
}

/**
 * Accessor for status
 * - Prevents recursion by not updating DB here.
 */
public function getStatusAttribute($value)
{
    // Always trust stored statuses for paid/cancelled
    if (in_array($value, ['paid', 'cancelled'])) {
        return $value;
    }

    // Mark as overdue when due date is past and still unpaid
    if ($this->due_date && now()->gt($this->due_date) && $value === 'unpaid') {
        // 🛠 Use getRawOriginal() to read the actual DB column directly
        if ($this->getRawOriginal('status') !== 'overdue') {
            $this->updateQuietly(['status' => 'overdue']);
        }
        return 'overdue';
    }

    return $value;
}




}

