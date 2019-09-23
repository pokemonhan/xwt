<?php

namespace App\Pay\Core;

/**
 * Class PayHandlerFactory
 * @package App\Pay\Core
 */
class PayHandlerFactory
{
    /**
     * @param string $className 类名称.
     * @param array  $params    实例化的参数.
     * @return mixed
     */
    private function generateClass(string $className, ?array $params)
    {
        return new $className($params);
    }

    /**
     * 生成支付方式的Handle
     * @param string $payName 支付方式标记.
     * @param array  $params  实例化前的参数.
     * @return mixed
     */
    public function generatePayHandle(string $payName, ?array $params)
    {
        return $this->generateClass('\App\\Pay\\'.ucfirst($payName).'Handler', $params);
    }
}
