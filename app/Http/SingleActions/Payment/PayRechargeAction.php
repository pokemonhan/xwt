<?php
namespace App\Http\SingleActions\Payment;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Http\Controllers\FrontendApi\FrontendApiMainController;
use App\Http\Controllers\FrontendApi\Pay\PayController;
use App\Lib\Pay\Panda;
use App\Models\Pay\BackendPaymentConfig;
use App\Models\Pay\BackendPaymentInfo;
use App\Models\User\Fund\FrontendUsersAccount;
use App\Models\User\Fund\FrontendUsersAccountsReport;
use App\Models\User\UserProfits;
use App\Pay\Core\PayHandlerFactory;
use Illuminate\Http\JsonResponse;
use App\Models\User\UsersRechargeHistorie;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\Frontend\Pay\RechargeRequest;
use App\Http\Requests\Frontend\Pay\RechargeCallbackRequest;
use Exception;
use App\Http\Requests\Frontend\Pay\RechargeList;
use App\Http\Requests\Backend\Users\Fund\RechargeListRequest;

class PayRechargeAction
{
    protected $backendPaymentConfig;
    protected $backendPaymentInfo;
    public function __construct(BackendPaymentConfig $backendPaymentConfig, BackendPaymentInfo $backendPaymentInfo)
    {
        $this->backendPaymentConfig = $backendPaymentConfig;
        $this->backendPaymentInfo = $backendPaymentInfo;
    }

    /**
     * 获取可用充值网关
     * @param FrontendApiMainController $contll
     * @return JsonResponse
     */
    public function getRechargeChannel(FrontendApiMainController $contll): JsonResponse
    {
        $pandaC = new  Panda() ;
        $result =  $pandaC->getRechargeChannel($contll->partnerUser, 'web');
        return $contll->msgOut(true, $result);
    }

    /**
     * 获取支付渠道 v2.0
     * @param PayController $contll
     * @return JsonResponse
     */
    public function getRechargeChannelNew(PayController $contll) :JsonResponse
    {
        try {
            $output = $this->backendPaymentInfo::join('backend_payment_configs', 'backend_payment_configs.id', '=', 'backend_payment_infos.config_id')
                ->where('backend_payment_configs.status', $this->backendPaymentConfig::STATUS_ENABLE)
                ->where('backend_payment_infos.status', $this->backendPaymentInfo::STATUS_ENABLE)
                ->where('backend_payment_configs.direction', $this->backendPaymentConfig::DIRECTION_IN)
                ->where('backend_payment_infos.direction', $this->backendPaymentInfo::DIRECTION_IN)
                ->select(
                    'backend_payment_configs.payment_type_sign', //支付种类的标记
                    'backend_payment_configs.payment_type_name', //支付种类的名称
                    'backend_payment_configs.request_mode',  //请求方式
                    'backend_payment_infos.front_name', //前台名称
                    'backend_payment_infos.front_remark',  //前台备注
                    'backend_payment_infos.payment_sign',  //支付方式标记
                    'backend_payment_infos.min',  //最小值
                    'backend_payment_infos.max',  //最大值
                )
                ->orderByDesc('sort')
                ->get();
            return $contll->msgOut(true, $output);
        } catch (\Exception $e) {
            return $contll->msgOut(false, [], '400', '系统错误');
        }
    }

    /**
     * 发起充值 v2.0
     * @param PayController $contll
     * @param array $inputDatas
     * @return mixed
     */
    public function recharge(PayController $contll, array $inputDatas)
    {
        //第一步验证金额是否符合通道所规定的最大最小值
        $payment = $this->backendPaymentInfo::where('payment_sign', $inputDatas['channel'])->first();
        if ($payment->min > $inputDatas['amount'] || $payment->max < $inputDatas['amount']) {
            return $contll->msgOut(false, [], '400', '充值金额不符合规定');
        }
        //第二步生成订单
        $amount = $inputDatas['amount'];
        $channel = $inputDatas['channel'];
        $from = $inputDatas['from'] ?? 'web';
        $order = UsersRechargeHistorie::createRechargeOrder($contll->currentAuth->user(), $amount, $channel, $from);
        //第三步组装支付所用的数据 抛给生成的handle去处理
        $payParams = [
            'payment_sign' => $inputDatas['channel'],
            'order_no' => $order->company_order_num,
            'money' => $order->amount,
        ];
        return PayHandlerFactory::getInstance()->generatePayHandle($inputDatas['channel'], $payParams)->handle();
    }

    /**
     * 发起充值
     * @param FrontendApiMainController $contll
     * @param RechargeRequest $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function dorRecharge(FrontendApiMainController $contll, RechargeRequest $request) : JsonResponse
    {
        $amount = $request->input('amount') ?? 0;
        $channel = $request->input('channel') ?? '';
        $from = $request->input('from') ?? 'web';

        $order = UsersRechargeHistorie::createRechargeOrder($contll->currentAuth->user(), $amount, $channel, $from);

        $pandaC = new  Panda() ;
        $result =  $pandaC->recharge($amount, $order->company_order_num, $channel, $from);

        if (array_get($result, 'status') === 'success') {
            return $contll->msgOut(true, $result);
        } else {
            return $contll->msgOut(false, $result);
        }
    }

    /**
     * 处理回调
     * @param RechargeCallbackRequest $request
     * @throws \Exception
     */
    public function rechageCallback(RechargeCallbackRequest $request)
    {
        Log::channel('pay-recharge')->info('callBackInfo:'.json_encode($request->all()));
        $data = $request->all() ;

        $pandaC = new  Panda() ;
        if ($pandaC->checkRechargeCallbackSign($data) === true) {
            $this->businessProcessing($pandaC, $data); //业务处理
        } else {
            Log::channel('pay-recharge')->error('验签失败:'.json_encode($request->all()));
        }

        $pandaC->renderSuccess();
    }

    /**
     * 回调业务处理
     * @param  object $pandaC
     * @param  array $data
     * @return void
     */
    private function businessProcessing($pandaC, $data)
    {
        DB::beginTransaction();
        $pandaC::setRechargeOrderStatus($data, array_get($data, 'status'));
        if ((int) array_get($data, 'status') === 1) {
            $userInfo = UsersRechargeHistorie::where('company_order_num', '=', $data['game_order_id'])->first();
            try {
                $params = [
                    'user_id' => $userInfo->user_id,
                    'amount' => array_get($data, 'money')
                ];
                $account  = FrontendUsersAccount::where('user_id', $userInfo->user_id)->first();

                $resouce = $account->operateAccount($params, 'recharge');
                if ($resouce !== true) {
                    DB::rollBack();
                }
                DB::commit();
                Log::channel('pay-recharge')->info('充值成功:company_order_num '.$data['game_order_id']);
            } catch (Exception $e) {
                DB::rollBack();
                Log::channel('pay-recharge')->info('异常:'.$e->getMessage().'|'.$e->getFile().'|'.$e->getLine());
            }
        }
    }
    /**
     * 用户充值申请列表
     * @param FrontendApiMainController $contll
     * @param RechargeList $request
     * @return JsonResponse
     */
    public function rechargeList(FrontendApiMainController $contll, RechargeList $request) : JsonResponse
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

        $rows = UsersRechargeHistorie::where($where)->paginate($count);

        return $contll->msgOut(true, $rows);
    }

    /**
     * 充值到账列表
     * @param FrontendApiMainController $contll
     * @return JsonResponse
     */
    public function realRechargeList(FrontendApiMainController $contll) : JsonResponse
    {
        $userInfo = $contll->currentAuth->user();
        $contll->inputs['user_id'] = $userInfo->id;
        $contll->inputs['extra_where']['method'] = 'whereIn';
        $contll->inputs['extra_where']['key'] = 'type_sign';
        $contll->inputs['extra_where']['value'] = UserProfits::TEAM_DEPOSIT_SIGN;

        $usersAccountsReportELoq = new FrontendUsersAccountsReport();
        $searchAbleFields = ['user_id', 'type_sign'];
        $orderFields = 'id';
        $orderFlow = 'desc';
        $data = $contll->generateSearchQuery($usersAccountsReportELoq, $searchAbleFields, 0, null, [], $orderFields, $orderFlow);

        return $contll->msgOut(true, $data);
    }

    /**
     * 后台充值申请列表
     * @param BackEndApiMainController $contll
     * @param RechargeListRequest $request
     * @return JsonResponse
     */
    public function backRechargeList(BackEndApiMainController $contll, RechargeListRequest $request) : JsonResponse
    {
        $count = $request->input('page_size') ?? 15;
        $timeConditionField = $request->input('time_condtions') ?? '';
        $timeConditions = Arr::wrap(json_decode($timeConditionField, true));

        if ($timeConditions) {
            $where =  $timeConditions;
        } else {
            $where = [];
        }

        $rows = UsersRechargeHistorie::where($where)->paginate($count);

        return $contll->msgOut(true, $rows);
    }
}
