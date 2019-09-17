<?php

namespace App\Console\Commands;

use App\Models\Admin\BackendAdminUser;
use App\Models\Admin\Fund\BackendAdminRechargePermitGroup;
use App\Models\Admin\Fund\BackendAdminRechargePocessAmount;
use App\Models\Admin\SystemConfiguration;
use App\Models\User\Fund\BackendAdminRechargehumanLog;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class AllocationRechargeFundControl extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'AllocationRechargeFund';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '定时发放人工充值额度';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $sysConfigures = new SystemConfiguration();
        $everyDayfund = $sysConfigures->select('value')->where('sign', 'admin_recharge_daily_limit')->value('value');
        $groups = BackendAdminRechargePermitGroup::pluck('group_id')->toArray();
        //拥有权限的管理员
        $admins = BackendAdminUser::from('backend_admin_users as admin')
            ->select('admin.*', 'fund.fund')
            ->leftJoin('backend_admin_recharge_process_amounts as fund', 'fund.admin_id', '=', 'admin.id')
            ->whereIn('group_id', $groups)
            ->get()->toArray();
        foreach ($admins as $admin) {
            if ($admin['fund'] < $everyDayfund) {
                $addFund = $everyDayfund - $admin['fund'];
                $adminFund = $admin['fund'] + $addFund;
                $type = BackendAdminRechargehumanLog::SYSTEM;
                $in_out = BackendAdminRechargehumanLog::INCREMENT;
                $comment = '[每日充值额度发放]=>>+' . $addFund . '|[目前额度]=>>' . $adminFund;
                $time = date('Y-m-d H:i:s');
                $editFundData = [
                    'fund' => $everyDayfund,
                    'updated_at' => $time,
                ];
                $flowsData = [
                    'admin_id' => $admin['id'],
                    'admin_name' => $admin['name'],
                    'comment' => $comment,
                    'type' => $type,
                    'in_out' => $in_out,
                    'created_at' => $time,
                    'updated_at' => $time,
                ];
                DB::beginTransaction();
                try {
                    BackendAdminRechargePocessAmount::where('admin_id', $admin['id'])->update($editFundData);
                    $rechargeLog = new BackendAdminRechargehumanLog();
                    $rechargeLog->insert($flowsData);
                    DB::commit();
                } catch (Exception $e) {
                    DB::rollBack();
                }
            }
        }
    }
}
