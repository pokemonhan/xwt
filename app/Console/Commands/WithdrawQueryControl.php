<?php
namespace App\Console\Commands;

use App\Models\User\UsersWithdrawHistorie;
use Illuminate\Console\Command;
use App\Jobs\WithdrawQuery;

class WithdrawQueryControl extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'WithdrawQuery';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '审核通过的提现单下发状态查询';

    /**
     * Execute the console command.
     *
     * @return mixed
     */

    public function handle()
    {
        $rows = UsersWithdrawHistorie::where('status', '=', UsersWithdrawHistorie::STATUS_AUDIT_SUCCESS)
            ->select('id', 'order_id')
            ->get();
        if (!empty($rows)) {
            foreach ($rows as $value) {
                dispatch(new WithdrawQuery($value));
            }
        }
    }
}
