<?php
namespace App\Http\Controllers\CasinoApi;

use App\Http\Controllers\FrontendApi\FrontendApiMainController;
use Illuminate\Support\Facades\Log;

class CasinoApiMainController extends FrontendApiMainController
{
    public $secretkey   = '6a0c49f5c3c828e82bd6870610f48092';
    public $apiUrl      = 'http://yibogj.com/';
    public $username    = 'baowang';    // 代理名称
    public $userIp      = '';    // 会员ip

    public $callIpNum   = 0;    // ip访问次数
    public $callIpTime  = 0;    // ip访问时间
    public $callMax     = 30;      // 接口访问最大限制次数

    public $cacheTimeName   = ''; // 缓存时间的名字

    /**
     * AdminMainController constructor.
     */
    public function __construct()
    {
        $this->userIp           = real_ip();
        $this->cacheTimeName    = $this->userIp . 'time';
        // 记录ip访问次数
        $this->callIpNum  = \Cache::get($this->userIp);
        $this->callIpTime = \Cache::get($this->cacheTimeName);

        $this->casinoApiLog();
        // 1. 访问接口 频繁 禁止访问
        // 1分钟之内访问大于 30次 则限制访问
        if ($this->callIpNum > $this->callMax) {
            return $this->msgOut(false, [], 100600, '访问频繁，请稍后再访问');
        }

        parent::__construct();
    }

    /**
     * api 访问频率控制
     */
    private function casinoApiLog(): void
    {
        \Cache::put($this->userIp, $this->callIpNum + 1);

        // 未访问接口，接口时间为空， 初始化接口时间
        if (empty($this->callIpTime)) {
            \Cache::put($this->cacheTimeName, time());
        }

        // 距离上次访问超过一分钟初始化接口时间
        if (time() - $this->callIpTime > 60 * 1000) {
            \Cache::put($this->cacheTimeName, time());
            \Cache::put($this->userIp, 0);
        }
    }

    /**
     * 传递参数加密
     * @param $string
     * @param string $operation
     * @param $platKey
     * @param int $expiry
     * @return bool|string
     */
    public function authcode($string, $operation, $platKey, $expiry = 0)
    {
        // 动态密匙长度，相同的明文会生成不同密文就是依靠动态密匙
        $ckey_length = 4;

        // 密匙
        $platKey = md5($platKey);

        // 密匙a会参与加解密
        $keya = md5(substr($platKey, 0, 16));

        // 密匙b会用来做数据完整性验证
        $keyb = md5(substr($platKey, 16, 16));

        // 密匙c用于变化生成的密文
        $keyc = $ckey_length ? ($operation === 'DECODE' ? substr($string, 0, $ckey_length) : substr(md5(microtime()), -$ckey_length)) : '';

        // 参与运算的密匙
        $cryptkey = $keya . md5($keya . $keyc);

        $key_length = strlen($cryptkey);
        // 明文，前10位用来保存时间戳，解密时验证数据有效性，10到26位用来保存$keyb(密匙b)，
        //解密时会通过这个密匙验证数据完整性
        // 如果是解码的话，会从第$ckey_length位开始，因为密文前$ckey_length位保存 动态密匙，以保证解密正确
        $string = $operation === 'DECODE' ? base64_decode(substr($string, $ckey_length)) :
            sprintf('%010d', $expiry ? $expiry + time() : 0) . substr(md5($string . $keyb), 0, 16) . $string;

        $string_length = strlen($string);

        $result = '';

        $boxRandom = range(0, 255);

        $rndkey = [];

        // 产生密匙簿
        for ($i = 0; $i <= 255; $i++) {
            $rndkey[$i] = ord($cryptkey[$i % $key_length]);
        }

        // 用固定的算法，打乱密匙簿，增加随机性，好像很复杂，实际上对并不会增加密文的强度
        for ($j = $i = 0; $i < 256; $i++) {
            $j = ($j + $boxRandom[$i] + $rndkey[$i]) % 256;
            $tmp = $boxRandom[$i];
            $boxRandom[$i] = $boxRandom[$j];
            $boxRandom[$j] = $tmp;
        }

        // 核心加解密部分
        for ($a = $j = $i = 0; $i < $string_length; $i++) {
            $a = ($a + 1) % 256;
            $j = ($j + $boxRandom[$a]) % 256;
            $tmp = $boxRandom[$a];
            $boxRandom[$a] = $boxRandom[$j];
            $boxRandom[$j] = $tmp;
            // 从密匙簿得出密匙进行异或，再转成字符
            $result .= chr(ord($string[$i]) ^ $boxRandom[($boxRandom[$a] + $boxRandom[$j]) % 256]);
        }

        if ($operation === 'DECODE') {
            // 验证数据有效性，请看未加密明文的格式
            if ((substr($result, 0, 10) === 0 || substr($result, 0, 10) - time() > 0) &&
                substr($result, 10, 16) === substr(md5(substr($result, 26) . $keyb), 0, 16)) {
                return substr($result, 26);
            } else {
                return '';
            }
        } else {
            // 把动态密匙保存在密文里，这也是为什么同样的明文，生产不同密文后能解密的原因
            // 因为加密后的密文可能是一些特殊字符，复制过程可能会丢失，所以用base64编码
            return $keyc . str_replace('=', '', base64_encode($result));
        }
    }

    /**
     * api请求
     * @param string $method
     * @param $url
     * @param array $params
     * @param null $header
     * @param bool $cuestomerquest
     * @param int $https
     * @param int $locaIp
     * @return bool|string
     */
    public function request($method, $url, $params = [], $header = null, $cuestomerquest = false, $https = 1, $locaIp = 1)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $url);
        if ($https) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        }
        if ($locaIp) {
            curl_setopt($ch, CURLOPT_INTERFACE, config('game.pub.AddressIp'));
        }
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLINFO_HEADER_OUT, false);

        if (!is_null($header)) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        }
        if ($cuestomerquest) {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        }
        $output ='';
        switch ($method) {
            case 'POST':
                curl_setopt($ch, CURLOPT_POST, true);
                if (is_array($params)) {
                    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
                } else {
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
                }
                $output = curl_exec($ch);
                Log::channel('casino-success')->info(json_encode($output));
                if (curl_errno($ch)) {
                    Log::channel('casino-err')->info(json_encode(curl_error($ch)));
                    curl_close($ch);
                    return false;
                }
                return $output;
                break;
            case 'GET':
                $output = curl_exec($ch);
                Log::channel('casino-success')->info(json_encode($output));
                if (curl_errno($ch)) {
                    Log::channel('casino-err')->info(json_encode(curl_error($ch)));
                    curl_close($ch);
                    return false;
                }
                return $output;
                break;
        }
    }
}
