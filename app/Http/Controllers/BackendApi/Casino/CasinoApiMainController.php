<?php
namespace App\Http\Controllers\BackendApi\Casino;

use App\Http\Controllers\Controller;

/**
 * Class CasinoApiMainController
 * @package App\Http\Controllers\BackendApi\Casino
 */
class CasinoApiMainController extends Controller
{
    /**
     * @var string
     */
    public $secretkey   = 'c518ae8a59bdb2fa89a943c7ab920669';
    /**
     * @var string
     */
    public $apiUrl      = 'http://52.69.242.200';
    /**
     * @var string
     */
    public $username    = 'xuanwu';

    /**
     * 传递参数加密
     * @param string  $string    字符串.
     * @param string  $operation 加密/解密.
     * @param string  $platKey   公钥.
     * @param integer $expiry    时间.
     * @return boolean
     */
    public function authcode(string $string, string $operation, string $platKey, int $expiry = 0)
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
     * @param string  $method         GET POST.
     * @param string  $url            Url.
     * @param array   $params         请求参数.
     * @param string  $header         请求头.
     * @param integer $cuestomerquest 请求类型.
     * @param integer $https          Https 1.
     * @param integer $locaIp         请求ip.
     * @return string
     */
    public function request(string $method, string $url, array $params, string $header, int $cuestomerquest, int $https, int $locaIp)
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

        if (!empty($header)) {
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
//                Log::channel('casino-success')->info(json_encode($output));
                if (curl_errno($ch)) {
//                    Log::channel('casino-err')->info(json_encode(curl_error($ch)));
                    curl_close($ch);
                    return false;
                }
                return $output;
                break;
            case 'GET':
                $output = curl_exec($ch);
//                Log::channel('casino-success')->info(json_encode($output));
                if (curl_errno($ch)) {
//                    Log::channel('casino-err')->info(json_encode(curl_error($ch)));
                    curl_close($ch);
                    return false;
                }
                return $output;
                break;
        }
    }

    /**
     * @param  boolean $success     S.
     * @param  string  $data        D.
     * @param  string  $code        C.
     * @param  string  $message     M.
     * @param  string  $placeholder P.
     * @param  string  $substituted S.
     * @return boolean
     */
    public function msgOut(bool $success, string $data, string $code, string $message, string $placeholder, string $substituted)
    {
        $defaultSuccessCode = '200';
        $defaultErrorCode = '404';
        if ($success === true) {
            $code = $code === '' ? $defaultSuccessCode : $code;
        } else {
            $code = $code === '' ? $defaultErrorCode : $code;
        }
        if ($placeholder === '' || $substituted === '') {
            $message = $message === '' ? __('frontend-codes-map.' . $code) : $message;
        } else {
            $message = $message === '' ? __('frontend-codes-map.' . $code, [$placeholder => $substituted]) : $message;
        }
        $datas = [
            'success' => $success,
            'code' => $code,
            'data' => $data,
            'message' => $message,
        ];
        return response()->json($datas);
    }
}
