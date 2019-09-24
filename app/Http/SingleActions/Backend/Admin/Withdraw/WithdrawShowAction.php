<?php

namespace App\Http\SingleActions\Backend\Admin\Withdraw;

use App\Http\Controllers\BackendApi\Admin\Withdraw\WithdrawController;
use App\Models\Project;
use App\Models\User\FrontendUser;
use App\Models\User\UserCommissions;
use App\Models\User\UserDaysalary;
use App\Models\User\UsersRechargeHistorie;
use App\Models\User\UsersWithdrawHistorie;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;

/**
 * Class WithdrawShowAction
 * @package App\Http\SingleActions\Backend\Admin\Withdraw
 */
class WithdrawShowAction
{
    /**
     * @var object $userWithdrawHistory 用户提现记录.
     */
    protected $userWithdrawHistory;
    /**
     * @var object $userWithdrawHistoryOpt 用户提现记录对应的操作.
     */
    protected $userWithdrawHistoryOpt;
    /**
     * @var object $frontendUser 用户.
     */
    protected $frontendUser;
    /**
     * @var object $frontendUserAccount 用户账户.
     */
    protected $frontendUserAccount;
    /**
     * @var object $userRechargeHistories 用户充值记录.
     */
    protected $userRechargeHistories;
    /**
     * @var object $userProjects 用户打码记录.
     */
    protected $userProjects;
    /**
     * @var object $userCommissions 用户的返点记录.
     */
    protected $userCommissions;
    /**
     * @var object $userDaySalaries 用户的日工资记录.
     */
    protected $userDaySalaries;
    /**
     * @var object $userBonuses 用户的分红记录.
     */
    protected $userBonuses;
    /**
     * @var object $bank 对应银行.
     */
    protected $bank;
    /**
     * @var object $model 模型.
     */
    protected $model;
    /**
     * @var array $outputData 数据.
     */
    private $outputData = [
        'id' => null, //提现记录id
        'user_id' => null, //用户的id
        'user_status' => null, //用户的状态
        'remark' => null, //提现备注
        'order_id' => null, //提现编号
        'username' => null, //用户名
        'user_balance' => null, //用户余额
        'usable_balance' => null, //可用余额
        'can_withdraw_balance' => null, //可提现余额
        'is_tester' => null, //是否是测试用户
        'that_day_recharge_amount' => null, //当天充值金额
        'that_day_withdraw_amount' => null, //当天提现金额
        'that_day_bet_amount' => null, //当天投注额
        'created_at' => null, //提现申请时间
        'amount' => null, //金额
        'bank_name' => null, //银行名称
        'card_number' => null, //卡号
        'card_username' => null, //户名
        'province' => null, //省
        'branch' => null, //开户行
        'branch_address' => null, //开户行地址
        'fail_remark' => null, //提现失败备注
        'check_remark' => null, //审核备注
        'status' => null, //状态
        'claimant' => null, //认领人
        'claim_time' => null, //认领时间
        'audit_manager' => null, //审核管理员
        'audit_time' => null, //审核时间
        'updated_at' => null, //到账时间
        'service_fee' => null, //手续费
        'remittance_amount' => null, //实际付款金额
        'channel' => null, //渠道
        'ip' => null, //网络地址
    ];
    /**
     * @var array $total 各种统计的数据.
     */
    private $total = [
        'r_w_ratio_total' => null, //充提比
        'recharge_total' => null, //充值合计
        'online_recharge_total' => null, //在线充值统计
        'person_recharge_total' => null, //人工充值统计
        'transfer_recharge_total' => null, //转账充值统计
        'withdraw_total' => null, //提现总计
        'bet_total' => null, //打码量
        'bonus_total' => null, //奖金
        'win_total' => null, //盈亏总计
        'commission_total' => null, //投注返点
        'agent_commission_total' => null, //代理佣金
        'day_salary_total' => null, //日工资
        'dividend_total' => null, //分红
        'claim_recharge_total' => null, //理赔充值
        'activity_gift_money_total' => null, //活动礼金
    ];
    /**
     * WithdrawShowAction constructor.
     * @param UsersWithdrawHistorie $usersWithdrawHistorie 提现记录.
     */
    public function __construct(UsersWithdrawHistorie $usersWithdrawHistorie)
    {
        $this->model = $usersWithdrawHistorie;
    }

    /**
     * @param array $inputDatas 数据.
     * @return void
     */
    private function initVarEnv(array $inputDatas)
    {
        $this->userWithdrawHistory = $this->model::where('id', $inputDatas['id'])->first(); //提现记录
        $this->userWithdrawHistoryOpt = $this->userWithdrawHistory->withdrawHistoryOpt;
        $this->frontendUser = $this->userWithdrawHistory->frontendUser; //用户
        $this->frontendUserAccount = $this->frontendUser->account; //用户账户
        $this->userRechargeHistories = $this->frontendUser->rechargeHistories; //用户充值记录
        $this->userProjects = $this->frontendUser->projects; //用户的打码记录
        $this->userCommissions = $this->frontendUser->commissions; //用户的返点记录
        $this->userDaySalaries = $this->frontendUser->daysalaries; //用户的日工资记录
        $this->userBonuses = $this->frontendUser->bonuses; //用户的分红记录
        $this->bank = $this->userWithdrawHistory->bank; //对应的银行
    }

    /**
     * @param WithdrawController $contll     控制器.
     * @param array              $inputDatas 数据.
     * @return JsonResponse
     */
    public function execute(WithdrawController $contll, array $inputDatas) : JsonResponse
    {
        $this->initVarEnv($inputDatas);
        if (isset($inputDatas['start_time']) && isset($inputDatas['end_time'])) {
            $this->getTotalByTime($inputDatas['start_time'], $inputDatas['end_time']);
        } else {
            $this->outputData['id'] = $inputDatas['id'];
            $this->outputData['user_id'] = $this->frontendUser->id;
            $this->outputData['username'] = $this->userWithdrawHistory->username;
            $this->outputData['user_status'] = $this->frontendUser->frozen_type; //非零属于风险用户
            $this->outputData['remark'] = $this->userWithdrawHistory->description;
            $this->outputData['order_id'] = $this->userWithdrawHistory->order_id;
            $this->outputData['user_balance'] = $this->frontendUserAccount->balance + $this->frontendUserAccount->frozen;
            $this->outputData['usable_balance'] = (float) ($this->frontendUserAccount->balance??0);
            $this->outputData['can_withdraw_balance'] = (float) ($this->frontendUserAccount->balance??0);
            $this->outputData['is_tester'] = $this->userWithdrawHistory->is_tester;
            $this->outputData['that_day_recharge_amount'] = $this->getRechargeTotalByTime(Carbon::now()->startOfDay(), Carbon::now()->endOfDay());
            $this->outputData['that_day_withdraw_amount'] = $this->getWithdrawTotalByTime(Carbon::now()->startOfDay(), Carbon::now()->endOfDay());
            $this->outputData['that_day_bet_amount'] = $this->getBetTotalByTime(Carbon::now()->startOfDay(), Carbon::now()->endOfDay());
            $this->outputData['created_at'] = $this->userWithdrawHistory->created_at->format('Y-m-d H:i:s');
            $this->outputData['amount'] = $this->userWithdrawHistory->amount??0;
            $this->outputData['bank_name'] = $this->bank->bank_name??'';
            $this->outputData['card_number'] = $this->userWithdrawHistory->card_number??'';
            $this->outputData['card_username'] = $this->userWithdrawHistory->card_username??'';
            $this->outputData['province'] = $this->bank->province->region_name??'';
            $this->outputData['branch'] = $this->bank->branch??'';
            $this->outputData['branch_address'] = ($this->outputData['province'] . ' ' ?? '') . $this->outputData['branch'];
            $this->outputData['fail_remark'] = $this->userWithdrawHistoryOpt->fail_remark ?? null;
            $this->outputData['check_remark'] = $this->userWithdrawHistoryOpt->check_remark ?? null;
            $this->outputData['status'] = $this->userWithdrawHistoryOpt->status ?? UsersWithdrawHistorie::STATUS_AUDIT_WAIT;
            $this->outputData['claimant'] = $this->userWithdrawHistoryOpt->claimant ?? null;
            $this->outputData['claim_time'] = $this->userWithdrawHistoryOpt->claim_time ?? null;
            $this->outputData['audit_manager'] = $this->userWithdrawHistoryOpt->audit_manager ?? null;
            $this->outputData['audit_time'] = $this->userWithdrawHistoryOpt->audit_time ?? null;
            $this->outputData['updated_at'] = $this->userWithdrawHistoryOpt->updated_at ?? null;
            $this->outputData['service_fee'] = 0; //先默认为零
            $this->outputData['remittance_amount'] = $this->userWithdrawHistoryOpt->remittance_amount ?? null;
            $this->outputData['channel'] = $this->userWithdrawHistoryOpt->channel_sign ?? null;
            $this->outputData['ip'] = $this->userWithdrawHistory->client_ip;
            $this->getTotalByTime(Carbon::now()->startOfDay()->format('Y-m-d H:i:s'), Carbon::now()->endOfDay()->format('Y-m-d H:i:s'));
        }
        return $contll->msgOut(true, array_merge($this->outputData, $this->total));
    }

    /**
     * 得到时间段内的各种统计数据
     * @param string $start_time 开始时间.
     * @param string $end_time   结束时间.
     * @return void
     */
    private function getTotalByTime(string $start_time, string $end_time)
    {
        $recharge_total = $this->getRechargeTotalByTime($start_time, $end_time);
        $withdraw_total = $this->getWithdrawTotalByTime($start_time, $end_time);
        $this->total['r_w_ratio_total'] = $recharge_total . '/'. $withdraw_total;
        $this->total['recharge_total'] = $recharge_total;
        $this->total['online_recharge_total'] = $this->getRechargeTotalByTime($start_time, $end_time, 1);
        $this->total['person_recharge_total'] = $this->getRechargeTotalByTime($start_time, $end_time, 2);
        $this->total['transfer_recharge_total'] = $this->getRechargeTotalByTime($start_time, $end_time, 2);
        $this->total['withdraw_total'] = $withdraw_total;
        $this->total['bet_total'] = $this->getBetTotalByTime($start_time, $end_time);
        $this->total['bonus_total'] = $this->getBonusTotalByTime($start_time, $end_time);
        $this->total['win_total'] = $this->getWinTotalByTime($start_time, $end_time);
        $this->total['commission_total'] = $this->getCommissionTotalByTime($this->userCommissions, $start_time, $end_time);
        $this->total['agent_commission_total'] = $this->getAgentCommissionTotalByTime($start_time, $end_time);
        $this->total['day_salary_total'] = $this->getDaySalariesTotalByTime($start_time, $end_time);
        $this->total['dividend_total'] = $this->getDividendTotalByTime($start_time, $end_time);
        //暂时为零
        $this->total['claim_recharge_total'] = 0;
        $this->total['activity_gift_money_total'] = 0;
    }
    //得到对应时间段内此用户的充值统计 type | 0 不分类 | 1 在线充值 | 2 人工充值 | 3 转账充值
    /**
     * 得到时间段内的各种统计数据
     * @param string  $start_time 开始时间.
     * @param string  $end_time   结束时间.
     * @param integer $type       类型.
     * @return float
     */
    private function getRechargeTotalByTime(string $start_time, string $end_time, int $type = 0) :float
    {
        $total = 0;
        if ($type === 0) {
            $total = $this->userRechargeHistories->where('status', UsersRechargeHistorie::STATUS_SUCCESS)->whereBetween('created_at', [$start_time, $end_time])->sum('amount');
        } elseif ($type === 1) {
            $total = $this->userRechargeHistories->where('deposit_mode', UsersRechargeHistorie::MODE_AUTOMATIC)->where('status', UsersRechargeHistorie::STATUS_SUCCESS)->whereBetween('created_at', [$start_time, $end_time])->sum('amount');
        } elseif ($type === 2) {
            $total = $this->userRechargeHistories->where('deposit_mode', UsersRechargeHistorie::MODE_ARTIFICIAL)->where('status', UsersRechargeHistorie::STATUS_SUCCESS)->whereBetween('created_at', [$start_time, $end_time])->sum('amount');
        } elseif ($type === 3) {
            $total = $this->userRechargeHistories->where('deposit_mode', UsersRechargeHistorie::MODE_ARTIFICIAL)->where('status', UsersRechargeHistorie::STATUS_SUCCESS)->whereBetween('created_at', [$start_time, $end_time])->sum('amount');
        }
        return $total;
    }
    /**
     * 得到对应时间段内此用户的提款统计
     * @param string $start_time 开始时间.
     * @param string $end_time   结束时间.
     * @return float
     */
    private function getWithdrawTotalByTime(string $start_time, string $end_time) :float
    {
        return $this->model::where('user_id', $this->userWithdrawHistory->user_id)->where('status', UsersWithdrawHistorie::STATUS_SUCCESS)->whereBetween('created_at', [$start_time, $end_time])->sum('amount');
    }
    /**
     * 得到对应时间段内此用户的打码统计
     * @param string $start_time 开始时间.
     * @param string $end_time   结束时间.
     * @return float
     */
    private function getBetTotalByTime(string $start_time, string $end_time) :float
    {
        return $this->userProjects->whereIn('status', [Project::STATUS_LOST, Project::STATUS_WON, Project::STATUS_PRIZE_SENT])->whereBetween('created_at', [$start_time, $end_time])->sum('total_cost');
    }
    /**
     * 得到对应时间段内此用户的奖金统计
     * @param string $start_time 开始时间.
     * @param string $end_time   结束时间.
     * @return float
     */
    private function getBonusTotalByTime(string $start_time, string $end_time) :float
    {
        return $this->userProjects->where('status', Project::STATUS_PRIZE_SENT)->whereBetween('created_at', [$start_time, $end_time])->sum('bonus');
    }
    /**
     * 得到对应时间段内此用户的盈亏统计
     * @param string $start_time 开始时间.
     * @param string $end_time   结束时间.
     * @return float
     */
    private function getWinTotalByTime(string $start_time, string $end_time) :float
    {
        $commissionTotal = $this->getCommissionTotalByTime($this->userCommissions, $start_time, $end_time);
        $bonusTotal = $this->getBonusTotalByTime($start_time, $end_time);
        $betTotal = $this->getBetTotalByTime($start_time, $end_time);
        return (float) sprintf('%0.4f', $bonusTotal + $commissionTotal - $betTotal);
    }
    /**
     * 得到对应时间段内此用户的返点统计
     * @param object $userCommissions 用户返点.
     * @param string $start_time      开始时间.
     * @param string $end_time        结束时间.
     * @return float
     */
    private function getCommissionTotalByTime(object $userCommissions, string $start_time, string $end_time) :float
    {
        return $userCommissions->where('status', UserCommissions::STATUS_DONE)->whereBetween('created_at', [$start_time, $end_time])->sum('amount');
    }

    /**
     * 得到对应时间段内此用户的代理佣金统计
     * @param string $start_time 开始时间.
     * @param string $end_time   结束时间.
     * @return float
     */
    private function getAgentCommissionTotalByTime(string $start_time, string $end_time) :float
    {
        $agentCommissionTotal = 0;
        $subMembers = FrontendUser::where('parent_id', $this->frontendUser->id)->get();
        foreach ($subMembers as $subMember) {
            $agentCommissionTotal += $this->getCommissionTotalByTime($subMember->commissions, $start_time, $end_time);
        }
        return $agentCommissionTotal;
    }

    /**
     * 得到对应时间段内此用户的日工资统计
     * @param string $start_time 开始时间.
     * @param string $end_time   结束时间.
     * @return float
     */
    private function getDaySalariesTotalByTime(string $start_time, string $end_time) :float
    {
        return $this->userDaySalaries->where('status', UserDaysalary::STATUS_YES)->whereBetween('date', [$start_time, $end_time])->sum('daysalary');
    }

    /**
     * 得到对应时间段内此用户的分红统计
     * @param string $start_time 开始时间.
     * @param string $end_time   结束时间.
     * @return float
     */
    private function getDividendTotalByTime(string $start_time, string $end_time) :float
    {
        return $this->userBonuses->whereBetween('created_at', [$start_time, $end_time])->sum('bonus_total');
    }
}
