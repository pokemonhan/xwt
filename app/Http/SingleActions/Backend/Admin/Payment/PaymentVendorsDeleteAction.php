<?php

namespace App\Http\SingleActions\Backend\Admin\Payment;

use App\Http\Controllers\BackendApi\Admin\Payment\PaymentVendorsController;
use App\Models\Admin\Payment\BackendPaymentVendor;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Exception;

/**
 * Class PaymentVendorsDeleteAction
 * @package App\Http\SingleActions\Backend\Admin\Payment
 */
class PaymentVendorsDeleteAction
{
    /**
     * @var BackendPaymentVendor $model 第三方厂商表模型.
     */
    protected $model;

    /**
     * @param  BackendPaymentVendor $backendPaymentVendor 第三方厂商表模型.
     */
    public function __construct(BackendPaymentVendor $backendPaymentVendor)
    {
        $this->model = $backendPaymentVendor;
    }

    /**
     * 删除第三方厂商表信息
     * @param PaymentVendorsController $contll     自己的控制器.
     * @param array                    $inputDatas 前端输入的删除信息.
     * @return JsonResponse
     * @throws \Exception 异常.
     */
    public function execute(PaymentVendorsController $contll, array $inputDatas): JsonResponse
    {
        DB::beginTransaction();
        try {
            //执行删除功能
            $this->model::destroy($inputDatas['id']);
            DB::commit();
            return $contll->msgOut(true);
        } catch (Exception $e) {
            DB::rollback();
            return $contll->msgOut(false, [], $e->getCode(), $e->getMessage());
        }
    }
}
