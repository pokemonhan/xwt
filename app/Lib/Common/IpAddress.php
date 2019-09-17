<?php

namespace App\Lib\Common;

use App\Models\Admin\SystemAddressIp;

class IpAddress
{
    protected $getAddressUrl = 'http://ip.taobao.com/service/getIpInfo.php';

    /**
     * 调用第三方接口查询IP地址
     * @param  string $ip [IP]
     * @return object  $addressIpELoq   [description]
     */
    public function getAddress($ip)
    {
        $arr = ['ip' => $ip];
        $urlData = http_build_query($arr);
        $url = $this->getAddressUrl . '?' . $urlData;
        $json = $this->curl($url);
        $data = json_decode($json, true);
        $addressIpELoq = $this->insertAddress($data['data']);
        return $addressIpELoq;
    }

    public function curl($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }

    /**
     * 将查询到的数据插入system_address_ips表
     * @param  array   $data             [IP地址数据]
     * @return object  $addressIpELoq
     */
    public function insertAddress($data)
    {
        $addData['ip'] = $data['ip'];
        $addData['country'] = $data['country'] === 'XX' ? null : $data['country'];
        $addData['region'] = $data['region'] === 'XX' ? null : $data['region'];
        $addData['city'] = $data['city'] === 'XX' ? null : $data['city'];
        $addData['county'] = $data['county'] === 'XX' ? null : $data['county'];
        $addressIpELoq = new SystemAddressIp();
        $addressIpELoq->fill($addData);
        $addressIpELoq->save();
        return $addressIpELoq;
    }
}
