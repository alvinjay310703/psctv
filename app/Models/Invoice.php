<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Invoice extends Model
{
    protected $fillable = [
        'subscription_id',
        'invoice_no',
        'description',
        'amount_due',
        'due_date',
        'status',
    ];

    protected $dates = [
        'due_date',
    ];

    /**
     * Boot method to auto-generate invoice numbers
     */
   protected static function booted()
{
    static::creating(function ($invoice) {
        // Auto-generate invoice number
        if (empty($invoice->invoice_no)) {
            $lastInvoice = self::latest('id')->first();
            $nextNumber  = $lastInvoice ? $lastInvoice->id + 1 : 1;
            $invoice->invoice_no = 'INV-' . date('Ymd') . '-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
        }

        // Default description if not provided
        if (empty($invoice->description)) {
            $invoice->description = 'Billing Charge';
        }

        // Default status if not provided
        if (empty($invoice->status)) {
            $invoice->status = 'unpaid';
        }
    });
}
    


    /**
     * Auto-update status whenever accessed
     */
    public function getStatusAttribute($value)
    {
        // Keep status if already paid
        if ($value === 'paid') {
            return $value;
        }

        // If overdue
        if ($this->due_date && Carbon::parse($this->due_date)->isPast() && $value === 'unpaid') {
            $this->updateQuietly(['status' => 'overdue']);
            return 'overdue';
        }

        return $value;
    }

    // Relationships
    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
