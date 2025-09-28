<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Subscription;

class GenerateInvoices extends Command
{
    protected $signature = 'invoices:generate';
    protected $description = 'Generate recurring invoices for active subscriptions';

    public function handle()
    {
        $subs = Subscription::where('status', 'active')
            ->whereDate('next_billing_date', '<=', now())
            ->get();

        foreach ($subs as $sub) {
            $invoice = $sub->invoices()->create([
                'description' => 'Recurring billing for ' . ($sub->package->name ?? 'Plan'),
                'amount_due'  => $sub->package->price,
                'due_date'    => now()->addDays(7),
            ]);

            // Move next billing date forward
            $sub->update([
                'next_billing_date' => now()->addMonth(),
            ]);

            $this->info("Generated invoice #{$invoice->invoice_no} for subscription {$sub->id}");
        }
    }
}
