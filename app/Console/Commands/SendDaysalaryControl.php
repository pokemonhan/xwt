<?php
namespace App\Console\Commands;

use App\Jobs\SendDaysalary;
use App\Models\User\UserDaysalary;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class SendDaysalaryControl extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'SendDaysalary';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '发放日工资脚本';

    /**
     * Execute the console command.
     *
     * @return mixed
     */

    public function handle()
    {


        $iYesterDay = Carbon::yesterday()->toDateString();
        $aDatas = UserDaysalary::where("date", $iYesterDay)->where('status', 0)->get(['id']);

        foreach ($aDatas as $oUserDaysalary) {
            $data['salary_id'] = $oUserDaysalary->id;
            dispatch(new SendDaysalary($data));
        }
    }
}
