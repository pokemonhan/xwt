<?php

namespace App\Jobs;

use App\Http\Controllers\BackendApi\Admin\Withdraw\WithdrawController;
use App\Http\SingleActions\Backend\Admin\Withdraw\WithdrawStatusAction;
use App\Models\User\Fund\FrontendUsersAccount;
use App\Models\User\UsersWithdrawHistorie;
use App\Models\User\UsersWithdrawHistoryOpt;
use App\Pay\Core\PayHandlerFactory;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Class WithdrawQuery
 * @package App\Jobs
 */
class WithdrawQuery implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * @var object
     */
    protected $data;
    /**
     * Create a new job instance.
     *
     * @param object $data 提现记录集合.
     */
    public function __construct(object $data)
    {
        $this->data = $data;
    }
    /**
     * @return boolean
     * @throws Exception 异常.
     */
    public function handle() :bool
    {
        dd($this->data);
        DB::beginTransaction();
        try {
            $payParams = [
                'payment_sign' => $this->data->channel_sign,
                'order_no' => $this->data->order_id,
                'money' => $this->data->real_amount,
            ];

            $result = (new PayHandlerFactory())->generatePayHandle($this->data->channel_sign, $payParams)->check();
            if ($result) {
                if ($this->operateAccount()) {
                    Log::channel('callback-log')->info('订单号：'.$this->data->order_id.'提现成功');
                    (new WithdrawStatusAction(new UsersWithdrawHistorie(), new UsersWithdrawHistoryOpt()))->execute((new WithdrawController()), ['id'=>$this->data->id,'status'=>UsersWithdrawHistorie::STATUS_SUCCESS]);
                    DB::commit();
                    return true;
                } else {
                    Log::channel('callback-log')->info('订单号：'.$this->data->order_id.'提现失败');
                    DB::rollBack();
                    return false;
                }
            } else {
                Log::channel('callback-log')->info('订单号：'.$this->data->order_id.'提现失败');
                return false;
            }
        } catch (Exception $e) {
            DB::rollBack();
            Log::channel('callback-execption')->info($e);
            return false;
        }
    }

    /**
     * @return boolean
     */
    private function operateAccount() :bool
    {
        $params = [
            'user_id' => $this->data->user_id,
            'amount' => $this->data->amount,
        ];
        $account = FrontendUsersAccount::where('user_id', $this->data->user_id)->first();
        if (isset($account)) {
            $resource = $account->operateAccount($params, 'withdraw_finish');
            return $resource;
        } else {
            return false;
        }
    }
}
