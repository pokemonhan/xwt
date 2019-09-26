<?php
/**
 * Created by PhpStorm.
 * author: Harris
 * Date: 8/14/2019
 * Time: 5:25 PM
 */

if (!function_exists('configure')) {
    /**
     * @param string $sysKey  系统的钥匙.
     * @param string $default 默认值.
     * @return object
     */
    function configure(?string $sysKey = null, ?string $default = null)
    {
        if (is_null($sysKey)) {
            return app('Configure');
        } else {
            return app('Configure')->get($sysKey, $default);
        }
    }
}

if (!function_exists('curl_post')) {
    /**
     * @param string  $requrl       请求地址.
     * @param array   $data         请求数据.
     * @param string  $user_agent   请求头.
     * @param integer $conn_timeout 链接超时时间.
     * @param integer $timeout      超时时间.
     * @return boolean|string|null
     */
    function curl_post(string $requrl, array $data, ?string $user_agent = null, int $conn_timeout = 7, int $timeout = 60)
    {
        $headers = [
            'Accept: application/json',
            'Accept-Encoding: deflate',
            'Accept-Charset: utf-8;q=1',
        ];
        if ($user_agent === null) {
            $user_agent = 'User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_0)
             AppleWebKit/537.36 (KHTML, like Gecko) Chrome/31.0.1650.57 Safari/537.36';
        }
        $headers[] = $user_agent;
        $curlH = curl_init();
        curl_setopt($curlH, CURLOPT_URL, $requrl);
        curl_setopt($curlH, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curlH, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curlH, CURLOPT_HEADER, 0);
        curl_setopt($curlH, CURLOPT_CONNECTTIMEOUT, $conn_timeout);
        curl_setopt($curlH, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curlH, CURLOPT_TIMEOUT, $timeout);
        if ($data) {
            curl_setopt($curlH, CURLOPT_POST, 1);
            curl_setopt($curlH, CURLOPT_POSTFIELDS, http_build_query($data));
        }
        $result = curl_exec($curlH);
        $httpcode = curl_getinfo($curlH, CURLINFO_HTTP_CODE);
        $error = curl_errno($curlH);
        curl_close($curlH);
        if ($error || ($httpcode !== 200)) {
            return null;
        }

        return $result;
    }
}

if (!function_exists('real_ip')) {
    /**
     * @return mixed|string
     */
    function real_ip()
    {
        if (isset($_SERVER['HTTP_CLIENT_IP']) && !empty($_SERVER['HTTP_CLIENT_IP'])) {
            return $_SERVER['HTTP_CLIENT_IP'];
        }
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && !empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            return strtok($_SERVER['HTTP_X_FORWARDED_FOR'], ',');
        }
        if (isset($_SERVER['HTTP_PROXY_USER']) && !empty($_SERVER['HTTP_PROXY_USER'])) {
            return $_SERVER['HTTP_PROXY_USER'];
        }
        if (isset($_SERVER['REMOTE_ADDR']) && !empty($_SERVER['REMOTE_ADDR'])) {
            return $_SERVER['REMOTE_ADDR'];
        } else {
            return '0.0.0.0';
        }
    }
}

/**
 * @param object $object 参数.
 * @return array
 */
function objectToArray(object $object)
{
    if (is_object($object)) {
        $object = get_object_vars($object);
    }
    if (is_array($object)) {
        return array_map(__FUNCTION__, $object);
    } else {
        return $object;
    }
}


if (!function_exists('casino_authcode')) {
    /**
     * 传递参数加密
     * @param string  $string    字符串.
     * @param string  $operation 加密/解密.
     * @param string  $platKey   公钥.
     * @param integer $expiry    时间.
     * @return boolean
     */
    function casino_authcode(string $string, string $operation, string $platKey, int $expiry)
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
            $tmp_hook = $boxRandom[$key_i_num];
            $boxRandom[$key_i_num] = $boxRandom[$key_j_num];
            $boxRandom[$key_j_num] = $tmp_hook;
        }

        // 核心加解密部分
        for ($a_key_num = $j_key_num = $i_key_num = 0; $i_key_num < $string_length; $i_key_num++) {
            $a_key_num = ($a_key_num + 1) % 256;
            $j_key_num = ($j_key_num + $boxRandom[$a_key_num]) % 256;
            $tmp_hook = $boxRandom[$a_key_num];
            $boxRandom[$a_key_num] = $boxRandom[$j_key_num];
            $boxRandom[$j_key_num] = $tmp_hook;
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
}

if (!function_exists('casino_request')) {
    /**
     * api请求
     * @param string  $method         GET POST.
     * @param string  $call_url       Url.
     * @param array   $params         请求参数.
     * @param string  $header         请求头.
     * @param boolean $cuestomerquest 请求类型.
     * @param integer $https          Https 1.
     * @param integer $locaIp         请求ip.
     * @return string
     */
    function casino_request(string $method, string $call_url, array $params, string $header, bool $cuestomerquest, int $https, int $locaIp)
    {
        $ch_hook = curl_init();
        curl_setopt($ch_hook, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch_hook, CURLOPT_URL, $call_url);
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
