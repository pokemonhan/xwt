<?php


namespace App\Pay\Core;

class PayHandlerFactory
{
    private static $instance;
    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * @param string $className
     * @param string $params
     * @return mixed
     */
    private function generateClass($className = '', $params = '')
    {
        return new $className($params);
    }

    /**
     * 生成支付方式的Handle
     * @param string $payName
     * @param string $params
     * @return mixed
     */
    public function generatePayHandle($payName = '', $params = '')
    {
        return $this->generateClass('\App\\Pay\\'.ucfirst($payName).'Handler', $params);
    }
}
