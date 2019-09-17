<?php

namespace App\Jobs;

use App\Models\Finance\UserRecharge;
use App\Models\Stat\UserStat;
use App\Models\Stat\UserStatDay;
use App\Models\User\FrontendUser;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StatUser implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    protected $data = null;
    protected $type = null;
    protected $player = null;
    protected $dateTime = null;
    protected $recordId = null;

    public function __construct($type, array $data)
    {
        $this->type = $type;
        $this->data = $data;
    }

    /**
     * 执行
     */
    public function handle()
    {
        // 日期验证
        $date = isset($this->data['date']) ?? date('Y-m-d H:i:s');
        //if (!$this->checkDateTime($date)) {
        if (!$this->checkDateTime()) {
            Log::channel('stat')->error('stat-user-invalid-date', ['data' => $this->data]);
            return true;
        }

        $this->dateTime = $date;

        // 记录ID
        $recordId = isset($this->data['record_id']) ?? 0;
        if (!$recordId) {
            Log::channel('stat')->error('stat-user-empty-record_id', ['data' => $this->data]);
            return true;
        }

        $this->recordId = $recordId;

        // 用户验证
        $playerId = isset($this->data['player_id']) ?? 0;
        if ($playerId) {
            Log::channel('stat')->error('stat-user-empty-id', ['data' => $this->data]);
            return true;
        }

        // 获取玩家
        $player = FrontendUser::find($playerId);
        if (!$player) {
            Log::channel('stat')->error('stat-user-empty-player', ['data' => $this->data]);
            return true;
        }

        $this->player = $player;

        $type = $this->type;
        $res = '';
        DB::beginTransaction();
        try {
            switch ($type) {
                case 'bet':
                    $res = $this->doStatBet();
                    break;
                case 'cancel':
                    $res = $this->doStatCancel();
                    break;
                case 'bonus':
                    $res = $this->doStatBonus();
                    break;
                case 'self_points':
                    $res = $this->doStatPoints(); // ('self')   暂时没使用到   代码规范   先注释
                    break;
                case 'child_points':
                    $res = $this->doStatPoints(); // ('child')   暂时没使用到   代码规范   先注释
                    break;
                case 'gift':
                    $res = $this->doStatGift();
                    break;
                case 'dividend':
                    $res = $this->doStatDividend();
                    break;
                case 'salary':
                    $res = $this->doStatSalary();
                    break;
                case 'parent_transfer':
                    $res = $this->doStatParentTransfer();
                    break;
                case 'system_transfer':
                    $res = $this->doStatSystemTransfer();
                    break;
                case 'recharge':
                    $res = $this->doStatRecharge();
                    break;
                case 'withdraw':
                    $res = $this->doStatWithdraw();
                    break;
            }

            if ($res !== true) {
                Log::channel('stat')->error('stat-user-error-type:'.$this->type.'-' . $res, ['data' => $this->data]);
                DB::rollback();
            }

            DB::commit();
        } catch (\Exception $e) {
            Log::channel('stat')->error('stat-user-exception-type:'.$this->type.'-' . '|' . $e->getFile() . '|' . $e->getLine() . '|' . $e->getMessage(), ['data' => $this->data]);
        }
    }

    // 统计投注
    protected function doStatBet()
    {
    }

    // 统计撤单
    protected function doStatCancel()
    {
    }

    // 统计奖金
    protected function doStatBonus()
    {
    }

    // 统计返点
    protected function doStatPoints()//$type = 'child' 暂时没使用到   代码规范   先注释
    {
    }

    // 统计礼金
    protected function doStatGift()
    {
    }

    // 统计分红
    protected function doStatDividend()
    {
    }

    // 统计日工资
    protected function doStatSalary()
    {
    }

    // 统计上级转账
    protected function doStatParentTransfer()
    {
    }

    // 统计系统转账
    protected function doStatSystemTransfer()
    {
    }

    // 统计系统提现
    protected function doStatWithdraw()
    {
    }

    // 统计充值
    protected function doStatRecharge()
    {

        // $record = UserRecharge::find($recordId);
        $player = $this->player;
        $change = [
            'recharge_count' => 1,
            'recharge_amount' => isset($this->data['amount']) ?? 0,
        ];
        $userStat = UserStat::where('user_id', $player->id)->first();
        if ($userStat === null) {
            $res = '数据不存在';
        }
        if ($userStat->recharge_amount <= 0) {
            $change['recharge_first'] = 1;
        }

        $date = strtotime($this->dateTime);
        if ($date !== false) {
            $dateDay = date('Ymd', $date);

            $res = UserStatDay::change($player, $change, $dateDay);
        } else {
            $res = '日期不正确';
        }

        if ($res !== true) {
            //Clog不知道哪里来的  暂时注释掉
            //Clog::statUser("stat-user-recharge-error-" . $player->id . "-充值-统计失败", ['params' => $change, 'res' => $res]);
            return $res;
        }

        return true;
    }

    // @TODO
    protected function checkDateTime()
    {
    }
}
