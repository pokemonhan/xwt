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
     * @var BackendPaymentVendor $model BackendPaymentVendor.
     */
    protected $model;

    /**
     * PaymentVendorsAddAction constructor
     * @param BackendPaymentVendor $backendPaymentVendor BackendPaymentVendor.
     */
    public function __construct(BackendPaymentVendor $backendPaymentVendor)
    {
        $this->model = $backendPaymentVendor;
    }

    /**
     * 执行添加第三方厂商表操作
     * @param PaymentVendorsController $contll     PaymentVendorsController.
     * @param array                    $inputDatas InputDatas.
     * @return JsonResponse
     */
    public function execute(PaymentVendorsController $contll, array $inputDatas): JsonResponse
    {
        try {
            //处理拼接数据
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
