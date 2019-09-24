<?php

namespace App\Pay;

use App\Pay\Core\BasePay;
use Curl\Curl;
use Illuminate\Support\Facades\Log;

/**
 * Class Panda_zfbscanHandler
 * @package App\Pay
 */
class Panda_zfbscanHandler extends BasePay
{
    /**
     * @return mixed|void
     * @throws \ErrorException 异常.
     */
    public function handle()
    {
        //1.组装数据
        $postData = [
            'merchant_id' => $this->payInfo['merchant_code'],
            'amount' => sprintf('%d', $this->payInfo['money']),
            'order_id' => $this->payInfo['order_no'],
            'source' => $this->payInfo['source'],
            'channel' => 'zfb',
            'callback_url' => $this->payInfo['callback_url'],
            'client_ip' => real_ip(),
            'time' => time(),
        ];
        //2.生成签名
        $postData['sign'] = $this->getSign($postData);
        Log::channel('post-data')->info($postData);
        //3.发起请求
        $requestRes = (new Curl())->post($this->payInfo['request_url'], $postData);
        $requestRes = json_decode(json_encode($requestRes), true);
        Log::channel('curl-res')->info($requestRes);
        if ($requestRes['status'] === 'success') {
            return redirect($requestRes['data']['pay_url']);
        } else {
            return $requestRes['msg']??'通道异常';
        }
    }

    /**
     * @param array $data 生成签名所需的参数.
     * @return string
     */
    private function getSign(array $data) :string
    {
        ksort($data);
        $signClear = urldecode(http_build_query($data)).'&key='.$this->payInfo['merchant_secret'];
        Log::channel('sign-clear')->info($signClear);
        $sign = md5($signClear);
        Log::channel('sign')->info($signClear.'------'.$sign);
        return $sign;
    }

    /**
     * @param array|null $data 回调参数.
     * @return array
     */
    public function verify(?array $data) :array
    {
        $originSign = $data['sign'];
        unset($data['sign']);
        $nowSign = $this->getSign($data);
        if ($originSign === $nowSign && $data['status'] === 1) {
            $this->verifyRes['flag'] = true;
        }
        $this->verifyRes['back_param'] = 'success';
        $this->verifyRes['order_money'] = $data['money']??0;
        $this->verifyRes['real_money'] = $data['money']??0;
        $this->verifyRes['merchant_order_no'] = $data['game_order_id'];
        return $this->verifyRes;
    }
}
