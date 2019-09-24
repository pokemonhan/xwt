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
