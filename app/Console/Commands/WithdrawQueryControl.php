<?php
namespace App\Console\Commands;

use App\Http\Controllers\BackendApi\Admin\Withdraw\WithdrawController;
use App\Http\SingleActions\Backend\Admin\Withdraw\WithdrawStatusAction;
use App\Models\User\Fund\FrontendUsersAccount;
use App\Models\User\UsersWithdrawHistorie;
use App\Models\User\UsersWithdrawHistoryOpt;
use App\Pay\Core\PayHandlerFactory;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Class WithdrawQueryControl
 * @package App\Console\Commands
 */
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
     * @var array $payments 需要主动检查订单的提现渠道.
     */
    protected $payments = [
        'panda_out',
    ];

    /**
     * Execute the console command.
     *
     * @return mixed
     * @throws \Exception 异常.
     */
    public function handle()
    {
        $rows = UsersWithdrawHistorie::join('users_withdraw_history_opts', 'users_withdraw_histories.id', '=', 'users_withdraw_history_opts.withdraw_id')
            ->where('users_withdraw_histories.status', UsersWithdrawHistorie::STATUS_AUDIT_SUCCESS)
            ->where('users_withdraw_history_opts.status', UsersWithdrawHistorie::STATUS_AUDIT_SUCCESS)
            ->whereIn('users_withdraw_history_opts.channel_sign', $this->payments)
            ->select(
                'users_withdraw_histories.id',
                'users_withdraw_histories.user_id',
                'users_withdraw_history_opts.channel_sign',
                'users_withdraw_histories.real_amount',
                'users_withdraw_histories.order_id',
                'users_withdraw_histories.amount',
            )
            ->get();
        if (!empty($rows)) {
            foreach ($rows as $value) {
                $this->checkOrder($value);
            }
        }
    }

    /**
     * @param object $data 数据.
     * @return boolean
     * @throws \Exception 异常.
     */
    public function checkOrder(object $data) :bool
    {
        DB::beginTransaction();
        try {
            $payParams = [
                'payment_sign' => $data->channel_sign,
                'order_no' => $data->order_id,
                'money' => $data->real_amount,
            ];
            $result = (new PayHandlerFactory())->generatePayHandle($data->channel_sign, $payParams)->check();
            if ($result) {
                Log::channel('callback-log')->info('订单号：'.$data->order_id.'提现成功');
                (new WithdrawStatusAction(new UsersWithdrawHistorie(), new UsersWithdrawHistoryOpt()))->execute((new WithdrawController()), ['id'=>$data->id,'status'=>UsersWithdrawHistorie::STATUS_SUCCESS]);
                DB::commit();
                return true;
            } else {
                Log::channel('callback-log')->info('订单号：'.$data->order_id.'提现失败');
                return false;
            }
        } catch (\Exception $e) {
            DB::rollBack();
            Log::channel('callback-execption')->info($e);
            return false;
        }
    }

    /**
     * @param object $data 数据.
     * @return boolean
     */
    private function operateAccount(object $data) :bool
    {
        $params = [
            'user_id' => $data->user_id,
            'amount' => $data->amount,
        ];
        $account = FrontendUsersAccount::where('user_id', $data->user_id)->first();
        if (isset($account)) {
            $resource = $account->operateAccount($params, 'withdraw_finish');
            return $resource;
        } else {
            return false;
        }
    }
}
