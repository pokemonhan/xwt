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
     * @var BackendPaymentVendor $model BackendPaymentVendor.
     */
    protected $model;

    /**
     * @param  BackendPaymentVendor $backendPaymentVendor BackendPaymentVendor.
     */
    public function __construct(BackendPaymentVendor $backendPaymentVendor)
    {
        $this->model = $backendPaymentVendor;
    }

    /**
     * 删除第三方厂商表信息
     * @param PaymentVendorsController $contll     PaymentVendorsController.
     * @param array                    $inputDatas InputDatas.
     * @return JsonResponse
     * @throws \Exception 异常.
     */
    public function execute(PaymentVendorsController $contll, array $inputDatas): JsonResponse
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
