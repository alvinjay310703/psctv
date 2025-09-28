<?php
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Customer;
use App\Models\Package;
use App\Models\Subscription;
use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Support\Facades\Hash;

class DummyDataSeeder extends Seeder
{
    public function run()
    {
        // Create a user if none exists
        $user = User::firstOrCreate(
            ['email' => 'john@example.com'],
            [
                'name' => 'John Customer',
                'password' => Hash::make('password'),
                'role' => 'customer',
            ]
        );

        // Create a customer linked to that user
        $customer = Customer::firstOrCreate(
            ['user_id' => $user->id],
            [
                'account_number' => 'CUST-001',
                'status' => 'active',
                'address' => '123 Test St',
                'city' => 'Panabo City',
                'province' => 'Davao del Norte',
                'zip_code' => '8105',
                'phone' => '09123456789',
                'dob' => '1990-05-15',
                'gender' => 'male',
            ]
        );

        // Create a package if none exists
        $package = Package::firstOrCreate(
            ['code' => 'BASIC'],
            [
                'name' => 'Basic Plan',
                'description' => '50 Mbps Internet + 80 Channels',
                'speed_mbps' => 50,
                'channels' => 80,
                'price' => 499.00,
                'billing_cycle' => 'monthly',
                'is_active' => true,
            ]
        );

        // Create a subscription
        $subscription = Subscription::firstOrCreate(
            ['customer_id' => $customer->id, 'package_id' => $package->id],
            [
                'start_date' => now()->subMonth(),
                'status' => 'active',
                'next_billing_date' => now()->addMonth(),
            ]
        );

        // Create an invoice
        $invoice = Invoice::firstOrCreate(
            ['invoice_no' => 'INV-2025-001'],
            [
                'subscription_id' => $subscription->id,
                'description' => 'Monthly Internet Fee - September',
                'amount_due' => 499.00,
                'due_date' => now()->addDays(7),
                'status' => 'unpaid',
            ]
        );

        // Create a payment
        Payment::firstOrCreate(
            ['reference' => 'RCPT-1001'],
            [
                'invoice_id' => $invoice->id,
                'amount' => 499.00,
                'method' => 'cash',
                'status' => 'confirmed',
                'paid_at' => now(),
            ]
        );
    }
}
