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
     * @var BackendPaymentConfig $model BackendPaymentConfig.
     */
    protected $model;

    /**
     * PaymentConfigsDeleteAction constructor.
     * @param BackendPaymentConfig $backendPaymentConfig BackendPaymentConfig.
     */
    public function __construct(BackendPaymentConfig $backendPaymentConfig)
    {
        $this->model = $backendPaymentConfig;
    }

    /**
     * @param PaymentConfigsController $contll     配置.
     * @param array                    $inputDatas 配置.
     * @return JsonResponse
     * @throws \Exception 异常.
     */
    public function execute(PaymentConfigsController $contll, array $inputDatas): JsonResponse
    {
        DB::beginTransaction();
        try {
            $pastDataEloq = $this->model::find($inputDatas['id']);
            $pastDataEloq->delete();
            DB::commit();
            return $contll->msgOut(true);
        } catch (Exception $e) {
            DB::rollback();
            return $contll->msgOut(false, [], $e->getCode(), $e->getMessage());
        }
    }
}
