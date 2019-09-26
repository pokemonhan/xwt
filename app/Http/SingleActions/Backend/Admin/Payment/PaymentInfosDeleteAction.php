<?php

namespace App\Http\SingleActions\Backend\Admin\Payment;

use App\Http\Controllers\BackendApi\Admin\Payment\PaymentInfosController;
use App\Models\Admin\Payment\PaymentInfo;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Exception;

/**
 * Class PaymentInfosDeleteAction
 * @package App\Http\SingleActions\Backend\Admin\Payment
 */
class PaymentInfosDeleteAction
{
    /**
     * @var PaymentInfo $model 支付方式详情表模型.
     */
    protected $model;

    /**
     * PaymentInfosDeleteAction constructor.
     * @param PaymentInfo $paymentInfo 支付方式详情表模型.
     */
    public function __construct(PaymentInfo $paymentInfo)
    {
        $this->model = $paymentInfo;
    }

    /**
     * 删除支付方式信息
     * @param PaymentInfosController $contll     主控制器.
     * @param array                  $inputDatas 前端获取删除信息.
     * @return JsonResponse
     * @throws \Exception 异常.
     */
    public function execute(PaymentInfosController $contll, array $inputDatas): JsonResponse
    {
        $pastDataEloq = $this->model::find($inputDatas['id']);
        $sort = $pastDataEloq->sort;
        DB::beginTransaction();
        try {
            $pastDataEloq->delete();
            //排序
            $this->model::where('sort', '>', $sort)->decrement('sort');
            DB::commit();
            return $contll->msgOut(true);
        } catch (Exception $e) {
            DB::rollback();
            return $contll->msgOut(false, [], $e->getCode(), $e->getMessage());
        }
    }
}
