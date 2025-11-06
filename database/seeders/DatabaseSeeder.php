<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Call your existing seeders here
        $this->call([
            UserSeeder::class,
            SubscriptionSeeder::class,
            ServiceRequestDemoSeeder::class,
        ]);
    }
}
