<?php

namespace App\Http\SingleActions\Backend\Admin\Payment;

use App\Http\Controllers\BackendApi\Admin\Payment\PaymentConfigsController;
use App\Models\Admin\Payment\BackendPaymentConfig;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

/**
 * Class PaymentConfigsDeleteAction
 * @package App\Http\SingleActions\Backend\Admin\Payment
 */
class PaymentConfigsDeleteAction
{
    /**
     * @var BackendPaymentConfig $model 支付配置信息模型.
     */
    protected $model;

    /**
     * PaymentConfigsDeleteAction constructor.
     * @param BackendPaymentConfig $backendPaymentConfig 支付配置信息模型.
     */
    public function __construct(BackendPaymentConfig $backendPaymentConfig)
    {
        $this->model = $backendPaymentConfig;
    }

    /**
     * 删除支付配置信息
     * @param PaymentConfigsController $contll     主控制器.
     * @param array                    $inputDatas 前端获取删除参数.
     * @return JsonResponse
     * @throws \Exception 异常.
     */
    public function execute(PaymentConfigsController $contll, array $inputDatas): JsonResponse
    {
        DB::beginTransaction();
        try {
            $this->model::destroy($inputDatas['id']);
            DB::commit();
            return $contll->msgOut(true);
        } catch (Exception $e) {
            DB::rollback();
            return $contll->msgOut(false, [], $e->getCode(), $e->getMessage());
        }
    }
}
