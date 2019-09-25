<?php

namespace App\Http\SingleActions\Backend\Admin\Payment;

use App\Http\Controllers\BackendApi\Admin\Payment\PaymentInfosController;
use App\Models\Admin\Payment\PaymentInfo;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Exception;

/**
 * Class PaymentInfosSortAction
 * @package App\Http\SingleActions\Backend\Admin\Payment
 */
class PaymentInfosSortAction
{
    /**
     * @var PaymentInfo $model PaymentInfo.
     */
    protected $model;

    /**
     * PaymentInfosSortAction constructor.
     * @param PaymentInfo $paymentInfo PaymentInfo.
     */
    public function __construct(PaymentInfo $paymentInfo)
    {
        $this->model = $paymentInfo;
    }

    /**
     * 支付方式详情表排序
     * @param PaymentInfosController $contll     PaymentInfosController.
     * @param array                  $inputDatas InputDatas.
     * @return JsonResponse
     * @throws \Exception 异常.
     */
    public function execute(PaymentInfosController $contll, array $inputDatas): JsonResponse
    {
        DB::beginTransaction();
        try {
            //上拉排序
            if ((int) $inputDatas['sort_type'] === 1) {
                $stationaryData = $this->model::find($inputDatas['front_id']);
                $stationaryData->sort = $inputDatas['front_sort'];
                $this->model::where([
                    ['sort', '>=', $inputDatas['front_sort']],
                    ['sort', '<', $inputDatas['rearWays_sort']],
                ])
                    ->increment('sort');
                //下拉排序
            } elseif ((int) $inputDatas['sort_type'] === 2) {
                $stationaryData = $this->model::find($inputDatas['rearWays_id']);
                $stationaryData->sort = $inputDatas['rearWays_sort'];
                $this->model::where([
                    ['sort', '>', $inputDatas['front_sort']],
                    ['sort', '<=', $inputDatas['rearWays_sort']],
                ])
                    ->decrement('sort');
            } else {
                return $contll->msgOut(false);
            }
            $stationaryData->save();
            DB::commit();
            return $contll->msgOut(true);
        } catch (Exception $e) {
            DB::rollback();
            return $contll->msgOut(false, [], $e->getCode(), $e->getMessage());
        }
    }
}
