<?php

namespace App\Console;

use App\Models\DeveloperUsage\TaskScheduling\CronJob;
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
        Commands\DeleteCachePicControl::class,
        Commands\AllocationRechargeFundControl::class,
        Commands\GenerateIssueControl::class,
        Commands\LotterySchedule::class,
        Commands\UserProfitsControl::class,
        Commands\UserDaysalaryControl::class,
        Commands\SendDaysalaryControl::class,
        Commands\UserBonusControl::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $scheduleArr = CronJob::select('command', 'param', 'schedule')
            ->where('status', CronJob::STATUS_OPEN)
            ->get()
            ->toArray();
        foreach ($scheduleArr as $scheduleItem) {
            $criterias = json_decode($scheduleItem['param'], true);
            if (empty($criterias)) {
                $schedule->command($scheduleItem['command'])->cron($scheduleItem['schedule']); //没有argument的情况
            } else {
                $schedule->command($scheduleItem['command'], [$criterias])->cron($scheduleItem['schedule']); //有argument的情况
            }
        }
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
