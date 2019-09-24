<?php


namespace App\Pay\Core;

use App\Models\Pay\PaymentInfo;

/**
 * Class BasePay
 * @package App\Pay\Core
 */
abstract class BasePay
{
    /**
     * @var array 支付前预装信息.
     */
    protected $payInfo = [
        'merchant_code' => null, //商户号
        'merchant_secret' => null, //商户秘钥
        'public_key' => null, //第三方公钥
        'private_key' => null, //第三方私钥
        'bank_code' => null, //网银支付时所选的银行code
        'money' => null, //付款金额
        'order_no' => null, //商户订单号
        'callback_url' => null, //回调地址
        'redirect_url' => null, //同步跳转地址
        'request_url' => null, //支付网关
        'app_id' => null, //设备号
        'payment_vendor_url' => null, //第三方域名
        'attach' => null, //附加信息
    ];

    /**
     * @var array 回调验签后返回给调用者的信息.
     */
    protected $verifyRes = [
        'flag' => false, //验签成功与否的标记
        'back_param' => 'success', //返回给支付厂商的应答
        'merchant_order_no' => null, // 商户订单号
        'payment_order_no' => null, // 支付厂商订单号
        'order_money' => null, //订单上的金额
        'real_money' => null, //实际付款金额
    ];

    /**
     * @var array 同步返回给调用者的信息.
     */
    protected $syncRes = [
        'pay_url' => null, //支付链接
        'order_no' => null, //订单号
        'money' => null, //金额
    ];

    /**
     * BasePay constructor.
     * @param array $params 初始化参数.
     */
    public function __construct(array $params)
    {
        $payment = PaymentInfo::where('payment_sign', $params['payment_sign'])->first();
        $this->payInfo['merchant_code'] = $payment->merchant_code;
        $this->payInfo['merchant_secret'] = $payment->merchant_secret;
        $this->payInfo['public_key'] = $payment->public_key;
        $this->payInfo['private_key'] = $payment->private_key;
        $this->payInfo['bank_code'] = ''; //待开发
        $this->payInfo['money'] = $params['money'];
        $this->payInfo['order_no'] = $params['order_no'];
        $this->payInfo['callback_url'] = $params['order_no'];
//        dd($this->payInfo);
    }

    /**
     * @return mixed
     */
    abstract public function handle();

    /**
     * @return mixed
     */
    abstract public function verify();
}
