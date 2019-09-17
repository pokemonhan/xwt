<?php

namespace App\Lib\Pay;

use App\Models\User\FrontendUser;
use Illuminate\Support\Facades\Log;

class Panda extends BasePay
{
    public $sign = 'panda';

    public $constant = [];

    public function __construct()
    {

        $merchantId = config('pay.panda.merchant_id'); //商户号
        $pandaKey = config('pay.panda.key'); //商户密匙
        $gateway = config('pay.panda.gateway'); //接口地址

        $this->constant = [
            'callback' => self::getNotifyUrl($this->sign),
            'url' => $gateway,
            'key' => $pandaKey,
            'merchantId' => $merchantId,
        ];
        $this->constant['recharge_channel_url'] = $this->constant['url'] . 'recharge_channel';
        $this->constant['recharge_url'] = $this->constant['url'] . 'recharge';
        $this->constant['withdrawal_url'] = $this->constant['url'] . 'payment';
        $this->constant['withdrawal_query_url'] = $this->constant['url'] . 'payment_query';
    }

    public function renderSuccess()
    {
        echo 'success';
        die;
    }

    public function renderFail()
    {
        echo 'fail';
        die;
    }

    /**
     * 获取支付渠道
     * @param FrontendUser $user
     * @param string $source
     * @return array
     */
    public function getRechargeChannel(FrontendUser $user, $source = 'phone')
    {
        $params = [
            'merchant_id' => $this->constant['merchantId'],
            'client_ip' => real_ip(),
            'source' => $source,
            'username' => $user->username,
            'user_level' => 1,
        ];
        Log::channel('pay-recharge')->info('recharge-channel:【充值通道，参数传递】' . json_encode($params));
        $params['sign'] = $this->encrypt($params, $this->constant['key']);
        $result = json_decode(curl_post($this->constant['recharge_channel_url'], $params), true);

        Log::channel('pay-recharge')->info('recharge-channel:【充值通道请求返回】', $result);
        if ($result['status']) {
            return $result['data'];
        }
        return [];
    }

    /**
     * 充值
     * @param int $amount
     * @param string $orderId
     * @param string $channel
     * @param string $source
     * @return mixed
     */
    public function recharge($amount, $orderId, $channel, $source = 'web')
    {
        $callbackUrl = self::getCallbackUrl($this->sign);
        $rechargeUrl = $this->constant['recharge_url'];
        $merchantId = $this->constant['merchantId'];

        $param = [];
        $param['merchant_id'] = $merchantId;
        $param['amount'] = $amount;
        $param['order_id'] = $orderId;
        $param['source'] = $source;
        $param['channel'] = $channel;

        $param['callback_url'] = $callbackUrl;
        $param['client_ip'] = real_ip();
        $param['time'] = time();

        $pandaKey = $this->constant['key'];

        $param['sign'] = $this->encrypt($param, $pandaKey);

        Log::channel('pay-recharge')->info('recharge:【发起充值，参数传递】' . json_encode($param));
        $result = json_decode(curl_post($rechargeUrl, $param), true);
        Log::channel('pay-recharge')->info('recharge:【充值请求返回】' . json_encode($result));

        return $result;
    }

    protected $platform_sign = null;

    //渠道id
    public function setSign($sign)
    {
        $this->platform_sign = $sign;
        return $this;
    }

    public function withdrawal($withDrawInfo)
    {
        $pandaKey = $this->constant['key'];
        $withdrawalUrl = $this->constant['withdrawal_url'];
        $merchant = $this->constant['merchantId'];

        $params = [
            'merchant_id' => $merchant,
            'order_id' => $withDrawInfo->order_id,
            'source' => 'web',
            'amount' => $withDrawInfo->amount,
            'bank_sign' => $withDrawInfo->bank_sign,
            'card_number' => $withDrawInfo->card_number,
            'card_username' => $withDrawInfo->card_username,
            'client_ip' => real_ip(),
        ];

        $params['sign'] = $this->encrypt($params, $pandaKey);

        Log::channel('pay-withdraw')->info('withdraw:【发起提现，参数传递】' . json_encode($params));
        $result = json_decode(curl_post($withdrawalUrl, $params), true);
        Log::channel('pay-withdraw')->info('withdraw:【提现请求返回】' . json_encode($result));
        if ($result['status'] === 'success') {
            return [true, $result['msg']];
        }

        return [false, $result['msg']];
    }

    /**
     * 体现单查询
     * @param  string $orderId
     * @return array
     */
    public function queryWithdrawOrderStatus(string $orderId)
    {

        $pandaKey = $this->constant['key'];
        $withQueryUrl = $this->constant['withdrawal_query_url'];
        $merchant = $this->constant['merchantId'];

        $params = [
            'merchant_id' => $merchant,
            'order_id' => $orderId,
            'client_ip' => real_ip(),
        ];

        $params['sign'] = $this->encrypt($params, $pandaKey);

        Log::channel('pay-withdraw')->info('withdraw-query:【发送请求】' . json_encode($params));
        $result = json_decode(curl_post($withQueryUrl, $params), true);

        Log::channel('pay-withdraw')->info('withdraw-query:【请求结果】' . json_encode($result));

        if ($result['status'] === 'success') {
            return [true, $result['msg']];
        }

        return [false, $result['msg']];
    }

    public function encrypt(array $data, $signKey)
    {
        $string = '';
        ksort($data);
        foreach ($data as $dataKey => $value) {
            if ('sign' === $dataKey || self::isEmpty($value)) {
                continue;
            }
            $string .= $dataKey . '=' . $value . '&';
        }
        $string .= 'key=' . $signKey;

        return md5($string);
    }

    // 异步验签方法
    public function checkRechargeCallbackSign($data, $keySign = 'sign')
    {
        $pandaKey = $this->constant['key'];
        $mySign = $this->encrypt($data, $pandaKey);
        if (isset($data[$keySign]) && !empty($data[$keySign]) && $data[$keySign] === $mySign) {
            return true;
        }
        return false;
    }

    public static function isEmpty($value)
    {
        return $value === null || $value === [] || $value === '';
    }
}
