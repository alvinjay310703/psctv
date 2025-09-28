<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Customer;
use App\Models\Package;
use App\Models\Subscription;
use App\Models\Invoice;
use App\Models\Payment;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        // Seed some packages (plans)
        $packages = [
            [
                'code' => 'PRO-MONTHLY',
                'name' => 'Pro Plan',
                'description' => 'Unlimited internet with 200 Mbps speed.',
                'speed_mbps' => 200,
                'channels' => 150,
                'price' => 999.00,
                'billing_cycle' => 'monthly',
                'is_active' => true,
            ],
            [
                'code' => 'BASIC-YEARLY',
                'name' => 'Basic Plan',
                'description' => 'Affordable yearly plan with 50 Mbps speed.',
                'speed_mbps' => 50,
                'channels' => 80,
                'price' => 4999.00,
                'billing_cycle' => 'yearly',
                'is_active' => true,
            ],
        ];

        foreach ($packages as $pkg) {
            Package::updateOrCreate(['code' => $pkg['code']], $pkg);
        }

        // Create 5 demo customers with users
        for ($i = 1; $i <= 5; $i++) {
            $user = User::factory()->create([
                'name' => fake()->name(),
                'email' => fake()->unique()->safeEmail(),
                'password' => bcrypt('password'),
            ]);

            $customer = Customer::create([
                'user_id'        => $user->id,
                'account_number' => 'ACC-' . strtoupper(uniqid()),
                'status'         => 'active',
                'address'        => fake()->streetAddress(),
                'city'           => fake()->city(),
                'province'       => fake()->state(),
                'zip_code'       => fake()->postcode(),
                'gender'         => fake()->randomElement(['male', 'female']),
                'dob'            => fake()->date(),
                'phone'          => fake()->phoneNumber(),
                'plan'           => null,
                'meta'           => null,
            ]);

            // Assign 1–2 subscriptions
            $pkgSamples = Package::inRandomOrder()->take(rand(1, 2))->get();
            foreach ($pkgSamples as $package) {
                $subscription = $customer->subscriptions()->create([
                    'package_id'       => $package->id,
                    'start_date'       => now()->subMonths(rand(1, 6)),
                    'end_date'         => $package->billing_cycle === 'yearly' ? now()->addYear() : null,
                    'status'           => 'active',
                    'next_billing_date'=> now()->addMonth(),
                ]);

                // Invoice
                $invoice = $subscription->invoices()->create([
                    'invoice_no'   => 'INV-' . strtoupper(uniqid()),
                    'amount_due'   => $subscription->package->price ?? 0,
                    'status'       => 'unpaid',
                    'due_date'     => now()->addDays(7),
                ]);

                // Payment (sometimes paid, sometimes unpaid)
                if (rand(0, 1)) {
                    $invoice->payments()->create([
                        'method'  => 'gcash',
                        'amount'  => $invoice->amount_due,
                        'paid_at' => now(),
                        'status'  => 'completed', // ✅ FIXED: match DB enum
                    ]);

                    $invoice->update(['status' => 'paid']);
                }
            }
        }
    }
}
