<?php

namespace App\Http\SingleActions\Backend\Admin\Payment;

use App\Http\Controllers\BackendApi\Admin\Payment\PaymentVendorsController;
use App\Models\Admin\Payment\BackendPaymentVendor;
use Illuminate\Http\JsonResponse;
use Exception;
use App\Lib\BaseCache;

/**
 * Class PaymentVendorsAddAction
 * @package App\Http\SingleActions\Backend\Admin\Payment
 */
class PaymentVendorsAddAction
{
    use BaseCache;

    /**
     * @var BackendPaymentVendor $model 第三方厂商表模型.
     */
    protected $model;

    /**
     * PaymentVendorsAddAction constructor
     * @param BackendPaymentVendor $backendPaymentVendor 第三方厂商表模型.
     */
    public function __construct(BackendPaymentVendor $backendPaymentVendor)
    {
        $this->model = $backendPaymentVendor;
    }

    /**
     * 执行添加第三方厂商表操作
     * @param PaymentVendorsController $contll     第三方厂商表控制器.
     * @param array                    $inputDatas 前台获取的参数.
     * @return JsonResponse
     */
    public function execute(PaymentVendorsController $contll, array $inputDatas): JsonResponse
    {
        try {
            //处理拼接前台获取的IP白名单数据
            if (!empty($inputDatas['whitelist_ips']) && isset($inputDatas['whitelist_ips'])) {
                $inputDatas['whitelist_ips'] = $inputDatas['whitelist_ips'] . '|';
                $inputDatas['whitelist_ips'] = trim($inputDatas['whitelist_ips'], '|');
            }
            //执行添加第三方厂商表操作
            $configure = new $this->model();
            $configure->fill($inputDatas);
            $configure->save();
            return $contll->msgOut(true);
        } catch (Exception $e) {
            return $contll->msgOut(false, [], $e->getCode(), $e->getMessage());
        }
    }
}
