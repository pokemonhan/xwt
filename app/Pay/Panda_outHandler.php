<?php


namespace App\Pay;

use App\Pay\Core\BasePay;
use Curl\Curl;
use Illuminate\Support\Facades\Log;

/**
 * Class Panda_outHandler
 * @package App\Pay
 */
class Panda_outHandler extends BasePay
{
    /**
     * @return mixed|void
     */
    public function handle()
    {
        //1.组装数据
        $postData = [
            'merchant_id' => $this->payInfo['merchant_code'],
            'order_id' => $this->payInfo['order_no'],
            'source' => 'web',
            'amount' => $this->payInfo['money'],
            'bank_sign' => $this->payInfo['bank_code'],
            'card_number' => $this->payInfo['card_number'],
            'card_username' => $this->payInfo['card_username'],
            'client_ip' => real_ip(),
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
     * @param array|null $data 回调数据.
     * @return array
     */
    public function verify(?array $data): array
    {
        $this->verifyRes['flag'] = false;
        $this->verifyRes['back_param'] = 'success';
        $this->verifyRes['order_money'] = $data['money']??0;
        $this->verifyRes['real_money'] = $data['money']??0;
        $this->verifyRes['merchant_order_no'] = $data['game_order_id'];
        return $this->verifyRes;
    }

    /**
     * @return boolean
     * @throws \ErrorException 异常.
     */
    public function check(): bool
    {
        $postData = [
            'merchant_id' => $this->payInfo['merchant_code'],
            'order_id' => $this->payInfo['order_no'],
            'client_ip' => real_ip(),
        ];
        $postData['sign'] = $this->getSign($postData);
        Log::channel('post-data')->info($postData);
        $request_url = $this->payInfo['request_url'].'_query';
        $requestRes = (new Curl())->post($request_url, $postData);
        $requestRes = json_decode(json_encode($requestRes), true);
        Log::channel('curl-res')->info($requestRes);
        return $requestRes['status'] === 'success';
    }
}
