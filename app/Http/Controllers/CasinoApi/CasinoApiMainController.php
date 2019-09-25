<?php
namespace App\Http\Controllers\CasinoApi;

use App\Http\Controllers\FrontendApi\FrontendApiMainController;

/**
 * Class CasinoApiMainController
 * @package App\Http\Controllers\CasinoApi
 */
class CasinoApiMainController extends FrontendApiMainController
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
     * AdminMainController constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 传递参数加密
     * @param string  $string    字符串.
     * @param string  $operation 加密/解密.
     * @param string  $platKey   公钥.
     * @param integer $expiry    时间.
     * @return boolean
     */
    public function authcode(string $string, string $operation, string $platKey, int $expiry)
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
        for ($key_num = 0; $key_num <= 255; $key_num++) {
            $rndkey[$key_num] = ord($cryptkey[$key_num % $key_length]);
        }

        // 用固定的算法，打乱密匙簿，增加随机性，好像很复杂，实际上对并不会增加密文的强度
        for ($key_j_num = $key_i_num = 0; $key_i_num < 256; $key_i_num++) {
            $key_j_num = ($key_j_num + $boxRandom[$key_i_num] + $rndkey[$key_i_num]) % 256;
            $tmp = $boxRandom[$key_i_num];
            $boxRandom[$key_i_num] = $boxRandom[$key_j_num];
            $boxRandom[$key_j_num] = $tmp;
        }

        // 核心加解密部分
        for ($a_key_num = $j_key_num = $i_key_num = 0; $i_key_num < $string_length; $i_key_num++) {
            $a_key_num = ($a_key_num + 1) % 256;
            $j_key_num = ($j_key_num + $boxRandom[$a_key_num]) % 256;
            $tmp = $boxRandom[$a_key_num];
            $boxRandom[$a_key_num] = $boxRandom[$j_key_num];
            $boxRandom[$j_key_num] = $tmp;
            // 从密匙簿得出密匙进行异或，再转成字符
            $result .= chr(ord($string[$i_key_num]) ^ $boxRandom[($boxRandom[$a_key_num] + $boxRandom[$j_key_num]) % 256]);
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
     * @param boolean $cuestomerquest 请求类型.
     * @param integer $https          Https 1.
     * @param integer $locaIp         请求ip.
     * @return string
     */
    public function request(string $method, string $url, array $params, string $header, bool $cuestomerquest, int $https, int $locaIp)
    {
        $ch_hook = curl_init();
        curl_setopt($ch_hook, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch_hook, CURLOPT_URL, $url);
        if ($https) {
            curl_setopt($ch_hook, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch_hook, CURLOPT_SSL_VERIFYHOST, false);
        }
        if ($locaIp) {
            curl_setopt($ch_hook, CURLOPT_INTERFACE, config('game.pub.AddressIp'));
        }
        curl_setopt($ch_hook, CURLOPT_HEADER, false);
        curl_setopt($ch_hook, CURLINFO_HEADER_OUT, false);

        if (!empty($header)) {
            curl_setopt($ch_hook, CURLOPT_HTTPHEADER, $header);
        }
        if ($cuestomerquest) {
            curl_setopt($ch_hook, CURLOPT_CUSTOMREQUEST, $method);
        }
        $output ='';
        switch ($method) {
            case 'POST':
                curl_setopt($ch_hook, CURLOPT_POST, true);
                if (is_array($params)) {
                    curl_setopt($ch_hook, CURLOPT_POSTFIELDS, http_build_query($params));
                } else {
                    curl_setopt($ch_hook, CURLOPT_POSTFIELDS, $params);
                }
                $output = curl_exec($ch_hook);
//                Log::channel('casino-success')->info(json_encode($output));
                if (curl_errno($ch_hook)) {
//                    Log::channel('casino-err')->info(json_encode(curl_error($ch)));
                    curl_close($ch_hook);
                    return false;
                }
                return $output;
                break;
            case 'GET':
                $output = curl_exec($ch_hook);
//                Log::channel('casino-success')->info(json_encode($output));
                if (curl_errno($ch_hook)) {
//                    Log::channel('casino-err')->info(json_encode(curl_error($ch)));
                    curl_close($ch_hook);
                    return false;
                }
                return $output;
                break;
        }
    }
}
