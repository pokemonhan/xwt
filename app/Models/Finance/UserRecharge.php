<?php

namespace App\Models\Finance;

use App\Models\BaseModel;
use Illuminate\Support\Facades\Request;

class UserRecharge extends BaseModel
{
    protected $table = 'user_recharge';

    const STATUS_INIT               = 0;
    const STATUS_SEND_SUCCESS       = 1;
    const STATUS_CALLBACK_SUCCESS   = 2;
    const STATUS_MANUAL_SUCCESS     = 3;
    const STATUS_SEND_FAIL          = -1;
    const STATUS_CALLBACK_FAIL      = -2;
    const STATUS_MANUAL_FAIL        = -3;

    // 状态
    public static $status = [
        0   => "初始化",
        1   => "代发成功",
        2   => "回调成功",
        3   => "人工成功",
        -1  => "代发失败",
        -2  => "回调失败",
        -3  => "人工失败",
    ];

    public static $handType = [
        2 => "人工成功",
        1 => "人工失败",
    ];

    /**
     * 上级ID
     * @param $topId
     * @param $c
     * @param bool $countTotal
     * @return array
     */
    public static function getList($topId, $c, $countTotal = false)
    {
        $query = self::where('top_id', $topId)->orderBy('id', 'desc');

        // 用ID
        if (isset($c['user_id']) && $c['user_id']) {
            $query->where('user_id', $c['user_id']);
        }

        // 用户名
        if (isset($c['username']) && $c['username']) {
            $query->where('username', trim($c['username']));
        }

        // 昵称
        if (isset($c['nickname']) && $c['nickname']) {
            $query->where('nickname', trim($c['nickname']));
        }

        // 上级
        if (isset($c['status']) && $c['status'] && $c['status'] != 'all') {
            if (is_array($c['status'])) {
                $query->whereIn('status', $c['status']);
            } else {
                $query->where('status', $c['status']);
            }
        }

        // 订单号
        if (isset($c['order_id']) && $c['order_id']) {
            $query->where('order_id', trim($c['order_id']));
        }

        // 时间
        if (isset($c['start_time']) && $c['start_time']) {
            $query->where('init_time', ">=", strtotime($c['start_time']));
        }

        // 时间
        if (isset($c['end_time']) && $c['end_time']) {
            $query->where('init_time', "<=", strtotime($c['end_time']));
        }

        $currentPage    = isset($c['pageIndex']) ? intval($c['pageIndex']) : 1;
        $pageSize       = isset($c['pageSize']) ? intval($c['pageSize']) : 15;
        $offset         = ($currentPage - 1) * $pageSize;

        //　统计总实际上分 == 需要优化
        $totalRealAmount = 0;
        if ($countTotal) {
            $totalRealAmount = $query->sum('real_amount');
        }

        $total  = $query->count();
        $data   = $query->skip($offset)->take($pageSize)->get();

        return [
            'data' => $data,
            'totalRealAmount' => number4($totalRealAmount),
            'total' => $total,
            'currentPage' => $currentPage,
            'totalPage' => intval(ceil($total / $pageSize))
        ];
    }

    /**
     * 请求充值
     * @param $user
     * @param $money
     * @param $channel
     * @param $bankSign
     * @param string $from
     * @param string $description
     * @return UserRecharge|bool
     */
    public static function request($user, $money, $channel, $bankSign, $from = "web", $description = '')
    {
        $params = Request::all();
        db()->beginTransaction();
        try {
            // 加入请求
            $request = new UserRecharge;
            $request->user_id       = $user->id;
            $request->top_id        = $user->top_id;
            $request->username      = $user->username;
            $request->nickname      = $user->nickname;
            $request->parent_id     = $user->parent_id;

            $request->channel       = $channel;             // 类型 支付宝
            $request->bank_sign     = $bankSign;

            $request->amount        = $money;               // 充值金额
            $request->init_time     = time();               // 请求时间
            $request->client_ip     = real_ip();            // 客户端IP
            $request->desc          = $description;         // 充值描述

            $request->sign          = "";                   // 附言
            $request->source        = $from;                // 来源

            $ret = $request->save();
            if (!$ret) {
                db()->rollback();
                return false;
            }

            Clog::rechargeLog("生成订单之前: param" . serialize($params));
            $rechargeOrderPlus = configure("finance_recharge_order_plus", 10000000);

            $request->order_id = "HB" . ($request->id + $rechargeOrderPlus);
            $ret    = $request->save();
            if (!$ret) {
                db()->rollback();
                return false;
            }

            db()->commit();
        } catch (\Exception $e) {
            db()->rollback();
            Clog::rechargeLog("充值:初始化异常:" . $e->getMessage() . "|" . $e->getLine() . "|" . $e->getFile());
            return false;
        }

        return $request;
    }

    /**
     * 上分
     * @param $realMoney
     * @param int $adminId
     * @param string $reason
     * @return bool
     */
    public function process($realMoney, $adminId = 0, $reason = "")
    {
        $player = Player::find($this->user_id);
        if (!$player) {
            return "对不起, 无效的玩家!!";
        }

        if ($realMoney > $this->amount) {
            return "对不起, 无效的上分资金!!";
        }

        if ($this->status > 1) {
            return "对不起, 订单已经处理!!";
        }


        $locker = new AccountLocker($player->id);
        if (!$locker->getLock()) {
            db()->rollback();
            return "对不起, 获取用户锁失败!!";
        }

        db()->beginTransaction();
        try {
            $account    = $player->account();

            // 充值上分
            $params = [
                'user_id'       => $player->id,
                'amount'        => $realMoney,
                'desc'          => $adminId ? $adminId . "|" . $reason : ""
            ];

            $accountChange = new AccountChange();
            $res = $accountChange->change($account, 'recharge', $params, $player->is_robot);
            if ($res !== true) {
                $locker->release();
                db()->rollback();
                return $res;
            }

            $this->real_amount      = $realMoney;
            $this->admin_id         = $adminId;
            $this->callback_time    = time();

            $this->status = $adminId ? self::STATUS_MANUAL_SUCCESS  : self::STATUS_CALLBACK_SUCCESS;
            $this->save();

            // 即时统计
            $res = $this->doStatRecharge($player);
            if ($res !== true) {
                $locker->release();
                db()->rollback();
                Clog::rechargeLog("对不起, 统计失败!!:", [$res]);
                return $res;
            }

            db()->commit();
        } catch (\Exception $e) {
            db()->rollback();
            Clog::rechargeLog("充值:上分异常:" . $e->getMessage() . "-" . $e->getLine(). "-" . $e->getFile());
            return  $e->getMessage();
        }

        $locker->release();

        // 总统计
        jtq(new Stat('recharge', ['user_id' => $player->id, 'record_id'  => $this->id]), 'stat_user');

        return true;
    }
}
