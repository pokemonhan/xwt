<?php

namespace App\Jobs;

use App\Lib\Pay\Panda;
use App\Models\User\Fund\FrontendUsersAccount;
use App\Models\User\UsersWithdrawHistorie;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WithdrawQuery implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    protected $data;

    /**
     * Create a new job instance.
     *
     * @param UsersWithdrawHistorie $data
     */
    public function __construct(UsersWithdrawHistorie $data)
    {
        $this->data = $data;
    }

    public function handle()
    {
        DB::beginTransaction();
        try {
            $pandaC = new Panda();
            $result = $pandaC->queryWithdrawOrderStatus($this->data->order_id);
            if (array_get($result, '0') === true) {
                $datas['id'] = $this->data->id;
                $datas['status'] = UsersWithdrawHistorie::STATUS_ARRIVAL;
                UsersWithdrawHistorie::setWithdrawOrder($datas);

                $userInfo = UsersWithdrawHistorie::where('order_id', '=', $this->data->order_id)->first();
                if ($userInfo !== null) {
                    return $this->operateAccount($userInfo, $this->data->order_id);
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } catch (Exception $e) {
            DB::rollBack();
            Log::channel('pay-withdraw')->info('异常:' . $e->getMessage() . '|' . $e->getFile() . '|' . $e->getLine());
        }
    }

    private function operateAccount($userInfo, $orderId)
    {
        $params = [
            'user_id' => $userInfo->user_id,
            'amount' => $userInfo->amount,
        ];
        $account = FrontendUsersAccount::where('user_id', $userInfo->user_id)->first();
        if ($account !== null) {
            $account->operateAccount($params, 'withdraw_un_frozen');
            $resource = $account->operateAccount($params, 'withdraw_finish');
            if ($resource !== true) {
                DB::rollBack();
                return false;
            }
            DB::commit();
            Log::channel('pay-withdraw')->info('提现成功:order_id ' . $orderId);
            return true;
        } else {
            return false;
        }
    }
}
