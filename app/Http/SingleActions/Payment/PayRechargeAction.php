<?php
namespace App\Http\SingleActions\Payment;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Http\Controllers\FrontendApi\FrontendApiMainController;
use App\Http\Requests\Backend\Users\Fund\RechargeListRequest;
use App\Http\Requests\Frontend\Pay\RechargeCallbackRequest;
use App\Http\Requests\Frontend\Pay\RechargeList;
use App\Http\Requests\Frontend\Pay\RechargeRequest;
use App\Lib\Pay\Panda;
use App\Models\Pay\BackendPaymentConfig;
use App\Models\Pay\PaymentInfo;
use App\Models\User\Fund\FrontendUsersAccount;
use App\Models\User\Fund\FrontendUsersAccountsReport;
use App\Models\User\UserProfits;
use App\Models\User\UsersRechargeHistorie;
use App\Pay\Core\PayHandlerFactory;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Class PayRechargeAction
 * @package App\Http\SingleActions\Payment
 */
class PayRechargeAction
{
    /**
     * @var BackendPaymentConfig 充值信息配置表模型.
     */
    protected $backendPaymentConfig;
    /**
     * @var PaymentInfo 充值信息表模型.
     */
    protected $paymentInfo;

    /**
     * PayRechargeAction constructor.
     * @param BackendPaymentConfig $backendPaymentConfig 充值信息配置表模型.
     * @param PaymentInfo          $paymentInfo          支付详情.
     */
    public function __construct(BackendPaymentConfig $backendPaymentConfig, PaymentInfo $paymentInfo)
    {
        $this->backendPaymentConfig = $backendPaymentConfig;
        $this->paymentInfo = $paymentInfo;
    }

    /**
     * 获取可用充值网关
     * @param FrontendApiMainController $contll 前端主控制器obj.
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
     * @param FrontendApiMainController $contll 自己的控制器.
     * @return JsonResponse
     */
    public function getRechargeChannelNew(FrontendApiMainController $contll) :JsonResponse
    {
        $output = PaymentInfo::getPaymentInfoLists();
        return $contll->msgOut(true, $output);
    }

    /**
     * 发起充值 v2.0
     * @param FrontendApiMainController $contll     自己的控制器.
     * @param array                     $inputDatas 前端输入的变量.
     * @return mixed
     */
    public function recharge(FrontendApiMainController $contll, array $inputDatas)
    {
        //第一步验证金额是否符合通道所规定的最大最小值
        $payment = $this->paymentInfo::where('payment_sign', $inputDatas['channel'])->first();
        if ($payment->min > $inputDatas['amount'] || $payment->max < $inputDatas['amount']) {
            return $contll->msgOut(false, [], '400', '充值金额不符合规定');
        }
        //第二步生成订单
        $amount = $inputDatas['amount'];
        $channel = $inputDatas['channel'];
        $from = $inputDatas['from'] ?? 'web';
        $payment_type_sign = $payment->payment_type_sign;
        $payment_id = $payment->id;
        $order = UsersRechargeHistorie::createRechargeOrder($contll->currentAuth->user(), $amount, $channel, $from, $payment_type_sign, $payment_id);
        //第三步组装支付所用的数据 抛给生成的handle去处理
        if (!empty($inputDatas['bank_code']) && !empty($banks_code = $payment->paymentConfig->banks_code)) { //获得支付厂商的bank_code
            $banks_code = explode('|', $banks_code);
            foreach ($banks_code as $item) {
                [$bank_code1,$bank_code2] = explode('=', $item);
                if ($bank_code1 === $inputDatas['bank_code']) {
                    $inputDatas['bank_code'] = $bank_code2;
                }
            }
        }
        $payParams = [
            'payment_sign' => $inputDatas['channel'],
            'order_no' => $order->company_order_num,
            'money' => $order->amount,
            'source' => $from,
            'bank_code' => $inputDatas['bank_code']??'',
        ];
        return (new PayHandlerFactory())->generatePayHandle($inputDatas['channel'], $payParams)->handle();
    }

    /**
     * 发起充值
     * @param FrontendApiMainController $contll  前端主控制器obj.
     * @param RechargeRequest           $request 验证器.
     * @return JsonResponse
     * @throws Exception 异常.
     */
    public function dorRecharge(FrontendApiMainController $contll, RechargeRequest $request) : JsonResponse
    {
        $amount = $request->input('amount') ?? 0;
        $channel = $request->input('channel') ?? '';
        $from = $request->input('from') ?? 'web';

        $order = UsersRechargeHistorie::createRechargeOrder($contll->currentAuth->user(), $amount, $channel, $from, 'panda', 0);

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
     * @param RechargeCallbackRequest $request 验证器.
     * @return void
     * @throws Exception 异常.
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
     * @param object $pandaC 支付类.
     * @param array  $data   回调数据.
     * @return void
     * @throws Exception 异常.
     */
    private function businessProcessing(object $pandaC, array $data)
    {
        DB::beginTransaction();
        $pandaC::setRechargeOrderStatus($data, array_get($data, 'status'));
        if ((int) array_get($data, 'status') === 1) {
            $userInfo = UsersRechargeHistorie::where('company_order_num', '=', $data['game_order_id'])->first();
            try {
                $params = [
                    'user_id' => $userInfo->user_id,
                    'amount' => array_get($data, 'money'),
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
     * @param FrontendApiMainController $contll  前端主控制器.
     * @param RechargeList              $request 验证器.
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
     * @param FrontendApiMainController $contll 前端主控制器.
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
     * @param BackEndApiMainController $contll  后端主控制器.
     * @param RechargeListRequest      $request 验证器.
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
