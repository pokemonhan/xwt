<?php

namespace App\Models\User\Fund\Logics;

use App\Jobs\UpdateUserProfits;
use App\Lib\Clog;
use App\Lib\Locker\AccountLocker;
use App\Lib\Logic\AccountChange;
use App\Models\Account\FrontendUsersAccountsType;
use Exception;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

/**
 * Created by PhpStorm.
 * author: Harris
 * Date: 5/31/2019
 * Time: 7:38 PM
 */
trait UserAccountLogics
{

    public static function getList($c): array
    {
        $query = self::select(
            DB::raw('frontend_users_accounts.*'),
            DB::raw('users.username'),
            DB::raw('users.prize_group')
        )->leftJoin('users', 'frontend_users_accounts.user_id', '=', 'users.id')->orderBy('id', 'desc');
        // 用户名
        if (isset($c['username']) && $c['username']) {
            $query->where('frontend_users_accounts.username', $c['username']);
        }
        // 上级
        if (isset($c['parent_name']) && $c['parent_name']) {
            $query->where('frontend_users_accounts.parent_name', $c['parent_name']);
        }
        $currentPage = $c['page_index'] ?? 1;
        $pageSize = $c['page_size'] ?? 15;
        $offset = ($currentPage - 1) * $pageSize;
        $total = $query->count();
        $data = $query->skip((int) $offset)->take($pageSize)->get();
        return [
            'data' => $data,
            'total' => $total,
            'currentPage' => $currentPage,
            'totalPage' => (int) ceil($total / $pageSize),
        ];
    }

    // 设置模式
    public function setChangeMode($mode): void
    {
        $this->mode = $mode;
    }

    // 执行帐变
    public function change($type, $params)
    {
        try {
            return $this->doChange($type, $params);
        } catch (\Exception $e) {
            //没有Clog类   暂时注释
            // Clog::account('error-' . $e->getMessage() . '|' . $e->getLine() . '|' . $e->getFile());
            return $e->getMessage();
        }
    }

    // 帐变逻辑
    public function doChange($typeSign, $params)
    {
        $user = $this->user();
        $typeConfig = FrontendUsersAccountsType::getTypeBySign($typeSign);
        //　1. 获取帐变配置
        if (empty($typeConfig)) {
            //没有Clog类   暂时注释
            // Clog::account('error-' . $user->id . '-' . $typeSign . '不存在!');
            return '对不起, ' . $typeSign . '不存在!';
        }
        // 2. 参数检测
        foreach ($typeConfig as $key => $value) {
            if (in_array($key, ['id', 'name', 'sign', 'type', 'froze_type'])) {
                continue;
            }
            if ($value === 1) {
                if (!isset($params[$key])) {
                    return '对不起, 参数' . $key . '没有传递!';
                }
            }
        }
        // 3. 检测金额
        $amount = abs($params['amount']);
        if ($amount === 0) {
            return true;
        }
        // 4. 关联用户是否存在
        $relatedUser = null;
        if (isset($params['related_id'])) {
            // 没有User类   暂时注释
            // $relatedUser = User::findByCache($params['related_id']);
            // if (!$relatedUser) {
            //     return '对不起, 无效的关联用户!';
            // }
        }
        // 冻结类型 1 冻结自己金额 2 冻结退还　3 冻结给玩家　4 冻结给系统　5 中奖
        // 资金增减. 需要检测对应
        if ($typeConfig['froze_type'] === 5) {
            if (!$relatedUser) {
                return '对不起, 必须存在关联用户!';
            }
            $relatedAccount = $relatedUser->account();
            $frozen = $relatedAccount->frozen;
            if ($frozen < $amount) {
                return '对不起, 相关用户可用冻结金额不足!';
            }
        }
        // 保存记录
        $report = [
            'serial_number' => self::getSerialNumber(),
            'user_id' => $user->id,
            'top_id' => $user->top_id,
            'parent_id' => $user->parent_id,
            'rid' => $user->rid,
            'username' => $user->username,
            'is_tester' => $user->is_tester,
            'type_sign' => $typeConfig['sign'],
            'type_name' => $typeConfig['name'],
            'related_id' => $params['related_id'] ?? 0,
            'admin_id' => $params['admin_id'] ?? 0,
            'room_id' => $params['room_id'] ?? 0,
            'project_id' => $params['project_id'] ?? 0,
            'froze_type' => $typeConfig['froze_type'],
            'desc' => $params['desc'] ?? 0,
            'day' => date('Ymd'),
            'amount' => $amount,
            'created_at' => date('Y-m-d H:i:s'),
        ];
        $beforeBalance = $this->balance;
        $beforeFrozen = $this->frozen;
        // 根据冻结类型处理
        switch ($typeConfig['froze_type']) {
            case self::FROZEN_STATUS_OUT:
                if ($params['amount'] > $this->balance) {
                    return '对不起, 用户余额不足!';
                }
                $ret = $this->frozen($amount);
                break;
            case self::FROZEN_STATUS_BACK:
                $ret = $this->unFrozen($amount);
                break;
            case self::FROZEN_STATUS_TO_PLAYER:
            case self::FROZEN_STATUS_TO_SYSTEM:
                if ($params['amount'] > $this->frozen) {
                    // 没有User类   暂时注释
                    //Clog::account('error-' . $user->id . '-' . $amount . '-' . $this->frozen . '-冻结金额不足!');
                    return '对不起, 用户冻结金额不足!';
                }
                $ret = $this->unFrozenToPlayer($amount);
                break;
            default:
                if ($typeConfig['type'] === 1) {
                    $ret = $this->add($params['amount']);
                } else {
                    if ($params['amount'] > $this->balance) {
                        return '对不起, 用户余额不足!';
                    }
                    $ret = $this->cost($params['amount']);
                }
        }
        if ($ret !== true) {
            return '对不起, 账户异常!';
        }
        $balance = $this->balance;
        $frozen = $this->frozen;
        $report['before_balance'] = $beforeBalance;
        $report['balance'] = $balance;
        $report['frozen_balance'] = $frozen;
        $report['before_frozen_balance'] = $beforeFrozen;
        $change['updated_at'] = date('Y-m-d H:i:s');
        $this->saveData($report);
        if ($beforeBalance !== $balance) {
            // 没有\App\Events\Broadcast\User   没有number2方法    暂时注释
            //event(new \App\Events\Broadcast\User($this->user_id, 'fundChange', ['balance' => number2($this->balance)]));
        }
        return true;
    }

    //没有用到的函数  规范整理 暂时注释
    /* public function triggerSave()
    {
    if ($this->changes) {
    DB::table('account_change_report')->insert($this->changes);
    // $ret = DB::table('account_change_report')->insert($this->changes);
    info($this->changes);
    // if (!$ret) {
    //     return false;
    // }
    $this->changes = [];
    }
    }*/

    public function saveData($report)
    {
        if ($this->mode === self::MODE_CHANGE_AFTER) {
            $this->changes[] = $report;
        } else {
            $ret = DB::table('account_change_report')->insert($report);
            if (!$ret) {
                return false;
            }
        }
        return true;
    }

    // 资金增加
    public function add($money): bool
    {
        $updated_at = date('Y-m-d H:i:s');
        $sql = 'update `frontend_users_accounts` set `balance`=`balance`+"' . $money . '" ,
`updated_at`="' . $updated_at . '"  where `user_id` ="' . $this->user_id . '"';
        $ret = DB::update($sql) > 0;
        if ($ret) {
            $this->balance += $money;
        }
        return $ret;
    }

    // 消耗资金
    public function cost($money): bool
    {
        $updated_at = date('Y-m-d H:i:s');
        $ret = DB::update('update `frontend_users_accounts` set `balance`=`balance`-"' . $money . '" ,
`updated_at`="' . $updated_at . '"  where `user_id` ="' . $this->user_id . '" and `balance`>="' . $money . '"') > 0;
        if ($ret) {
            $this->balance -= $money;
        }
        return $ret;
    }

    // 冻结资金
    public function frozen($money)
    {
        $updated_at = date('Y-m-d H:i:s');
        $ret = DB::update('update `frontend_users_accounts` set `balance`=`balance`-"' . $money . '",
`frozen`=`frozen`+ "' . $money . '"  ,
`updated_at`="' . $updated_at . '" where `user_id` ="' . $this->user_id . '" and `balance`>="' . $money . '"') > 0;
        if ($ret) {
            $this->balance -= $money;
            $this->frozen += $money;
        }
        return $ret;
    }

    // 解冻
    public function unFrozen($money)
    {
        $updated_at = date('Y-m-d H:i:s');
        $ret = DB::update('update `frontend_users_accounts` set `balance`=`balance`+"' . $money . '",
`frozen`=`frozen`- "' . $money . '" , `updated_at`="' . $updated_at . '"  where `user_id` ="' . $this->user_id . '"') > 0;
        if ($ret) {
            $this->balance += $money;
            $this->frozen -= $money;
        }
        return $ret;
    }

    // 解冻 - 到其他玩家头上
    public function unFrozenToPlayer($money)
    {
        $updated_at = date('Y-m-d H:i:s');
        $ret = DB::update('update `frontend_users_accounts` set  `frozen`=`frozen`- "' . $money . '" ,
`updated_at`="' . $updated_at . '"  where `user_id` ="' . $this->user_id . '"') > 0;
        if ($ret) {
            $this->frozen -= $money;
        }
        return $ret;
    }

    public function operateAccount($params, $type)
    {
        $accountLocker = new AccountLocker($this->user->id);
        if (!$accountLocker->getLock()) {
            return '对不起, 获取账户锁失败!';
        }
        try {
            // 帐变
            $accountChange = new AccountChange();
            $accountChange->setReportMode(AccountChange::MODE_REPORT_NOW);
            $accountChange->setChangeMode(AccountChange::MODE_CHANGE_NOW);
            $res = $accountChange->doChange($this, $type, $params);
            if ($res !== true) {
                $accountLocker->release();
                return '对不起, ' . $res;
            }
//            $accountChange->triggerSave();
            $accountLocker->release();
            //推入消息队列处理更新用户盈亏
            dispatch(new UpdateUserProfits($this->user->id));

            return true;
        } catch (Exception $e) {
            $accountLocker->release();
            Log::info('投注-异常:' . $e->getMessage() . '|' . $e->getFile() . '|' . $e->getLine());
            return '对不起, ' . $e->getMessage() . '|' . $e->getFile() . '|' . $e->getLine();
        }
    }

    /**
     * 生成帐变编号
     * @return string
     */
    public static function getSerialNumber(): string
    {
        return Str::orderedUuid()->getHex();
    }
}
