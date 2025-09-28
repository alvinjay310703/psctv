<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
   protected function schedule(Schedule $schedule)
{
    $schedule->call(function () {
        $subscriptions = \App\Models\Subscription::where('status', 'active')
            ->whereNotNull('end_date')
            ->where('end_date', '<', now())
            ->get();

        foreach ($subscriptions as $sub) {
            $sub->update(['status' => 'expired']);
        }
    })->everyMinute();  // 👈 change daily() to everyMinute()
}

    protected function commands()
    {
        $this->load(__DIR__.'/Commands');
        if (file_exists(base_path('routes/console.php'))) {
            require base_path('routes/console.php');
        }
    }
}
