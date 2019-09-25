<?php
namespace App\Http\SingleActions\Payment;

use App\Http\Controllers\FrontendApi\FrontendApiMainController;
use App\Lib\Pay\Panda;
use App\Models\Finance\Withdraw;
use App\Models\User\FrontendUser;
use App\Models\User\Fund\FrontendUsersAccountsReport;
//use App\Models\User\Fund\FrontendUsersBankCard;
use Illuminate\Http\JsonResponse;
use App\Models\User\UsersWithdrawHistorie;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\BackendApi\BackEndApiMainController;
use Illuminate\Support\Facades\Request;
use App\Http\Requests\Frontend\Pay\RechargeList;
use App\Http\Requests\Frontend\Pay\WithdrawRequest;

/**
 * Class PayWithdrawAction
 * @package App\Http\SingleActions\Payment
 */
class PayWithdrawAction
{

    /**
     * 发起提现
     * @param FrontendApiMainController $contll  前端主控制器.
     * @param WithdrawRequest           $request 验证器.
     * @return JsonResponse
     * @throws \Exception 异常.
     */
    public function applyWithdraw(FrontendApiMainController $contll, WithdrawRequest $request) : JsonResponse
    {
        $data['amount']         = $request->input('amount') ?? 0;
        $data['bank_sign']      = $request->input('bank_sign') ?? '';
        $data['card_number']    = $request->input('card_number') ?? '';
        $data['card_username']  = $request->input('card_username') ?? '';
        $data['from']           = $request->input('from') ?? 'web';

        $user = $contll->currentAuth->user();

        $fundPassword = request('fund_password', '');
        if (!$fundPassword || !Hash::check($fundPassword, $user->fund_password)) {
            return $contll->msgOut(false, [], '403', '对不起, 无效的资金密码!');
        }
        // 提现维护
        $withdrawMaintain = configure('finance_withdraw_maintain', '0');
        if ((int) $withdrawMaintain === 1) {
            return $contll->msgOut(false, '对不起, 提现维护中!');
        }
        // 检查是否提现时间
        if (!Withdraw::isDrawTime()) {
            return $contll->msgOut(false, '对不起, 当前时间不在提现开放时间内!');
        }
        // 测试账户不能提现
        if ($user->is_tester) {
            return $contll->msgOut(false, '对不起, 测试用户不能提现!');
        }
        //  检查资金是否锁定 账户是否是冻结
        if (!Withdraw::isFrozen($user)) {
            return $contll->msgOut(false, '对不起, 冻结用户不能提现!');
        }
        // 检查用户是否绑定银行卡     ################getCards方法不存在  暂时注释################
        /*$bindCards = FrontendUsersBankCard::getCards($user->id);
        if (count($bindCards) === 0) {
            return $contll->msgOut(false, '对不起, 用户没有绑定银行卡!');
        }*/
        //额外的验证
        $verification = $this->WithdrawVerification($user);
        if ($verification !== true) {
            return $contll->msgOut(false, $verification);
        }

        $result = UsersWithdrawHistorie::createWithdrawOrder($contll->currentAuth->user(), $data);
        if ($result) {
            return $contll->msgOut(true, $result);
        } else {
            return $contll->msgOut(false, $result);
        }
    }

    /**
     * @param object $user 用户.
     * @return boolean|string
     */
    private function WithdrawVerification(object $user)
    {
        // 检查提现未完成的单子
        $notFinishedOrder = Withdraw::where('user_id', $user->id)->whereIn('status', [0, 1, 2, 3])->count();
        if ($notFinishedOrder > 0) {
            return '对不起, 您有一笔未处理的订单, 请联系客服处理!!';
        }
        // 获取可用余额 余额为０　还能提现么？
        $account = $user->account();
        $userBalance = $account->balance;
        if ($userBalance <= 0) {
            return  '对不起, 用户资金不足!!';
        }
        //########################Help类不存在 代码规范暂时注释########################
        /*$amount = request('amount',         0);// 提现金额
        if (!$amount || $amount !== (int) $amount) {
            return Help::returnApiJson('对不起, 无效的资金输入!', 0);
        }
        // 金额范围
        $minWithdrawAmount = configure('finance_min_withdraw', 100);
        $maxWithdrawAmount = configure('finance_max_withdraw', 5000);
        if ($amount > $maxWithdrawAmount || $amount < $minWithdrawAmount) {
            return Help::returnApiJson('对不起, 提现的金额不在允许的范围内!', 0);
        }
        if ($userBalance < $amount * 10000) { // 余额是否足够
            return Help::returnApiJson('对不起, 用户资金不足!', 0);
        }
        $cardId  = request('card_id',        0); // 银行卡
        if (!$cardId || $cardId !== (int) $cardId) {
            return Help::returnApiJson('对不起, 无效的银行卡!', 0);
        }
        if (!array_key_exists($cardId, $bindCards)) {
            return Help::returnApiJson('对不起, 银行卡不存在!', 0);
        }
        $card = $bindCards[$cardId];
        // 提现的银行卡　是否绑定了超过 N 个小时
        if (time() - $card['update_time'] < configure('finance_card_withdraw_limit_hour', 2) * 3600) {
            return Help::returnApiJson('对不起, 银行卡绑定超过' . configure('finance_card_withdraw_limit_hour', 2) . '小时才能提现!', 0);
        }
        // 检查提现 成功　次数
        $todayDrawTimes = $user->getTodayDrawCount();
        if ($todayDrawTimes >= configure('finance_day_withdraw_count', 5)) {
            return Help::returnApiJson('对不起, 今日已经提'.$todayDrawTimes.'次!', 0);
        }
        //  至少有一笔抢包或者发包
        $resource = $user->haveFundChange();
        if ($resource !== true) {
            return Help::returnApiJson($resource, 0);
        }*/
        // $source = request('from',        "iphone"); // 没用到的暂时注释
        return true;
    }

    /**
     * 发起提现 v2.0
     * @param FrontendApiMainController $contll     前端主控制器.
     * @param array                     $inputDatas 数据.
     * @return JsonResponse
     * @throws \Exception 异常.
     */
    public function applyWithdrawNew(FrontendApiMainController $contll, array $inputDatas) :JsonResponse
    {
        $user = $contll->currentAuth->user();
        $bank = $user->banks()->where('id', $inputDatas['card_id'])->first();
        // 提现维护
        $withdrawMaintain = configure('finance_withdraw_maintain', '0');
        if ((int) $withdrawMaintain === 1) {
            return $contll->msgOut(false, [], '400', '对不起, 提现维护中!');
        }
        // 检查是否提现时间
        if (!$this->isDrawTime()) {
            return $contll->msgOut(false, [], '400', '对不起, 当前时间不在提现开放时间内!');
        }
        // 测试账户不能提现
        if ($user->is_tester) {
            return $contll->msgOut(false, [], '400', '对不起, 测试用户不能提现!');
        }
        //  检查资金是否锁定 账户是否是冻结
        if ($user->frozen_type === FrontendUser::FROZEN_TYPE_NO_WITHDRAWAL) {
            return $contll->msgOut(false, [], '400', '对不起, 冻结用户不能提现!');
        }
        // 检查提现未完成的单子
        $notFinishedOrder = UsersWithdrawHistorie::where('user_id', $user->id)->whereIn('status', [UsersWithdrawHistorie::STATUS_AUDIT_WAIT, UsersWithdrawHistorie::STATUS_CLAIMED,UsersWithdrawHistorie::STATUS_AUDIT_SUCCESS])->count();
        if ($notFinishedOrder > 0) {
            return $contll->msgOut(false, [], '400', '对不起, 您用未完成的提现订单, 请联系客服处理!!');
        }
        //资金密码是否正确
        if (!$inputDatas['fund_password'] || !Hash::check($inputDatas['fund_password'], $user->fund_password)) {
            return $contll->msgOut(false, [], '403', '对不起, 无效的资金密码!');
        }
        //账户资金是否充足
        $userBalance = $user->account->balance??0;
        if ($userBalance < $inputDatas['amount']) {
            return $contll->msgOut(false, [], '400', '对不起, 用户资金不足!!');
        }
        $data['amount']         = $inputDatas['amount'];
        $data['bank_sign']      = $bank->bank_sign??'';
        $data['card_number']    = $bank->card_number??'';
        $data['card_username']  = $bank->owner_name;
        $data['card_id']        = $bank->id;
        $data['from']           = $inputDatas['from'] ?? 'web';
        $result = UsersWithdrawHistorie::createWithdrawOrder($contll->currentAuth->user(), $data);
        if ($result) {
            return $contll->msgOut(true, $result);
        } else {
            return $contll->msgOut(false, $result);
        }
    }

    /**
     * @return boolean
     */
    public function isDrawTime() :bool
    {
        $drawTimeRange = configure('finance_withdraw_time_range', '00:00:00-02:00:00|09:30:00-24:00:00');
        $range = explode('|', $drawTimeRange);

        $nowSeconds = time();
        $nowDay     = date('Y-m-d ');
        foreach ($range as $item) {
            $r_time = explode('-', $item);

            if ($nowSeconds >= strtotime($nowDay . $r_time[0]) && $nowSeconds <= strtotime($nowDay . $r_time[1])) {
                return true;
            }
        }
        return false;
    }

    /**
     * 提现详情
     * @param BackEndApiMainController $contll 控制器.
     * @return JsonResponse
     */
    public function detail(BackEndApiMainController $contll) : JsonResponse
    {
        $id = Request::input('id') ?? '';
        $result = UsersWithdrawHistorie::find($id);
        if (!empty($result)) {
            return $contll->msgOut(true, $result);
        } else {
            return $contll->msgOut(false, $result);
        }
    }


    /**
     * 后台审核通过 提现
     * @param BackEndApiMainController $contll 控制器.
     * @return JsonResponse
     */
    public function auditSuccess(BackEndApiMainController $contll) : JsonResponse
    {
        $datas['id'] = Request::input('id') ?? '';
        $datas['process_time'] = time();
        $datas['admin_id'] = $contll->currentAuth->user()->id ;
        $datas['status'] = UsersWithdrawHistorie::STATUS_AUDIT_SUCCESS ;

        $result = UsersWithdrawHistorie::setWithdrawOrder($datas);
        Log::channel('pay-withdraw')->info('withdraw:【后台审核通过】' . json_encode($datas));
        if ($result) {
            //发起提现请求到panda
            $pandaC = new  Panda() ;
            $withDrawInfo = UsersWithdrawHistorie::find($datas['id']) ;
            $result =  $pandaC->withdrawal($withDrawInfo);

            if ($result[0] === false) {
                $datas['status'] = UsersWithdrawHistorie::STATUS_AUDIT_WAIT ;
                $withDrawInfo->update($datas);
            }
            return $contll->msgOut($result[0], '', '', $result[1]);
        } else {
            return $contll->msgOut(false, $result);
        }
    }

    /**
     * 后台审核不通过 提现
     * @param BackEndApiMainController $contll 控制器.
     * @return JsonResponse
     */
    public function auditFailure(BackEndApiMainController $contll) : JsonResponse
    {
        $datas['id'] = Request::input('id') ?? '';
        $datas['process_time'] = time();
        $datas['admin_id'] = $contll->currentAuth->user()->id ;
        $datas['status'] = UsersWithdrawHistorie::STATUS_AUDIT_FAILURE ;

        $result = UsersWithdrawHistorie::setWithdrawOrder($datas);
        if ($result) {
            return $contll->msgOut(true, $result);
        } else {
            return $contll->msgOut(false, $result);
        }
    }


    /**
     * 后台提现申请列表
     * @param BackEndApiMainController $contll 控制器.
     * @return JsonResponse
     */
    public function backWithdrawList(BackEndApiMainController $contll) : JsonResponse
    {
        $searchAbleFields = ['username', 'is_tester', 'order_id', 'card_number', 'card_username', 'source'];
        $data = $contll->generateSearchQuery(UsersWithdrawHistorie::class, $searchAbleFields);
        return $contll->msgOut(true, $data);
    }



    /**
     * 用户提现申请列表
     * @param FrontendApiMainController $contll  控制器.
     * @param RechargeList              $request 验证器.
     * @return JsonResponse
     */
    public function withdrawList(FrontendApiMainController $contll, RechargeList $request) : JsonResponse
    {
        $count = $request->input('page_size') ?? 15;
        $timeConditionField = $request->input('time_condtions') ?? '';
        $timeConditions = Arr::wrap(json_decode($timeConditionField, true));

        $userInfo = $contll->currentAuth->user() ;

        if ($timeConditions) {
            $where = array_merge([['user_id', $userInfo->id]], $timeConditions);
        } else {
            $where = [['user_id', $userInfo->id]];
        }

        $rows = UsersWithdrawHistorie::where($where)->paginate($count);

        return $contll->msgOut(true, $rows);
    }


    /**
     * 提现到账列表
     * @param FrontendApiMainController $contll  控制器.
     * @param RechargeList              $request 验证器.
     * @return JsonResponse
     */
    public function realWithdrawList(FrontendApiMainController $contll, RechargeList $request) : JsonResponse
    {
        $count = $request->input('page_size') ?? 15;
        $timeConditionField = $request->input('time_condtions') ?? '';
        $timeConditions = Arr::wrap(json_decode($timeConditionField, true));
        $userInfo = $contll->currentAuth->user() ;

        if ($timeConditions) {
            $where = array_merge([['user_id', $userInfo->id]], $timeConditions);
        } else {
            $where = [['user_id', $userInfo->id]];
        }

        $rows = FrontendUsersAccountsReport::where($where)->whereIn('type_sign', [11,12,13])
            ->paginate($count);

        return $contll->msgOut(true, $rows);
    }
}
