<?php

namespace App\Jobs;

use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use App\Models\User\UserDaysalary;
use App\Models\User\Fund\FrontendUsersAccount;
use Illuminate\Support\Facades\DB;

class SendDaysalary implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $datas;

    /**
     * Create a new job instance.
     *
     * @param $datas
     */
    public function __construct($datas)
    {
        $this->datas = $datas;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $send_salary_id = $this->datas['salary_id'];
        $oUserDaysalary = UserDaysalary::find($send_salary_id);
        if (!$oUserDaysalary) {
            Log::channel('daysalary')->error('日工资数据ID不存在!'.$send_salary_id) ;
        }
        if ($oUserDaysalary->status == 1) {
            Log::channel('daysalary')->error('日工资已发放!'.$send_salary_id) ;
        }
        if ($oUserDaysalary->daysalary == 0) {
            $oUserDaysalary->status=1;
            $oUserDaysalary->save();
        }
        DB::beginTransaction();

        try {
            $params = [
                'user_id' => $oUserDaysalary->user_id,
                'amount' => $oUserDaysalary->daysalary,
            ];
            $account  = FrontendUsersAccount::where('user_id', $oUserDaysalary->user_id)->first();
            $res = $account->operateAccount($params, 'day_salary');
            if ($res !== true) {
                DB::rollBack();
            }
            if ($res) {
                $oUserDaysalary->status = 1;
                $oUserDaysalary->sent_time = date("Y-m-d H:i:s");
                $oUserDaysalary->save();
                Log::channel('daysalary')
                    ->info($oUserDaysalary->username." salaray:".$oUserDaysalary->daysalary.'日工资已发放!');
            }
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            Log::channel('daysalary')->info('异常:'.$e->getMessage().'|'.$e->getFile().'|'.$e->getLine());
        }
    }
}
