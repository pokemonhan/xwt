<?php

namespace App\Http\SingleActions\Backend\Admin\Payment;

use App\Http\Controllers\BackendApi\Admin\Payment\PaymentTypesController;
use App\Models\Admin\Payment\BackendPaymentType;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Exception;

/**
 * Class PaymentTypesDeleteAction
 * @package App\Http\SingleActions\Backend\Admin\Payment
 */
class PaymentTypesDeleteAction
{
    /**
     * @var BackendPaymentType $model 支付方式类型表模型.
     */
    protected $model;

    /**
     * @param BackendPaymentType $backendPaymentType 支付方式类型表模型.
     */
    public function __construct(BackendPaymentType $backendPaymentType)
    {
        $this->model = $backendPaymentType;
    }

    /**
     * 删除支付方式类型表信息
     * @param PaymentTypesController $contll     支付方式类型表主控制器.
     * @param array                  $inputDatas 前端获取参数.
     * @return JsonResponse
     * @throws \Exception 异常.
     */
    public function execute(PaymentTypesController $contll, array $inputDatas): JsonResponse
    {
        DB::beginTransaction();
        try {
            //执行删除
            $this->model::destroy($inputDatas['id']);
            DB::commit();
            return $contll->msgOut(true);
        } catch (Exception $e) {
            DB::rollback();
            return $contll->msgOut(false, [], $e->getCode(), $e->getMessage());
        }
    }
}
