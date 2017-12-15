<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\DepositNotification::class,
    	Commands\NonActivatedSalesman::class,
    	Commands\ActivatedSalesman::class,
    	Commands\VoidUniqueCode::class,
        Commands\GenerateCsvDataSalesMlm::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('notification:deposit')->everyThirtyMinutes();
        // $schedule->command('notification:deposit')->everyMinute();
        $schedule->command('notification:deposit')->twiceDaily(9,13);
        $schedule->command('nonActivated:salesman')->days(['1','2','3','4','5','6'])->at('18:00');
        $schedule->command('activated:salesman')->days(['1','2','3','4','5','6'])->at('09:00');
        $schedule->command('void:uniqueCode')->dailyAt('00:00');
        $schedule->command('generate:csv-data-sales-mlm')->dailyAt('00:05');
        
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
