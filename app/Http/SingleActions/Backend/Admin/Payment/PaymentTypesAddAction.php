<?php

namespace App\Http\SingleActions\Backend\Admin\Payment;

use App\Http\Controllers\BackendApi\Admin\Payment\PaymentTypesController;
use App\Lib\Common\ImageArrange;
use App\Models\Admin\Payment\BackendPaymentType;
use Illuminate\Http\JsonResponse;
use App\Lib\BaseCache;
use Exception;

/**
 * Class PaymentTypesAddAction
 * @package App\Http\SingleActions\Backend\Admin\Payment
 */
class PaymentTypesAddAction
{
    use BaseCache;

    /**
     * @var BackendPaymentType $model 支付方式类型表模型.
     */
    protected $model;

    /**
     * PaymentTypesAddAction constructor
     * @param BackendPaymentType $backendPaymentType 支付方式类型表模型.
     */
    public function __construct(BackendPaymentType $backendPaymentType)
    {
        $this->model = $backendPaymentType;
    }

    /**
     * 执行添加支付方式类型表操作
     * @param PaymentTypesController $contll     支付方式类型表模型控制器.
     * @param array                  $inputDatas 前端获取参数.
     * @return JsonResponse
     */
    public function execute(PaymentTypesController $contll, array $inputDatas): JsonResponse
    {
        $imageObj = new ImageArrange();
        $folderName = 'payment_type';
        $depositPath = $imageObj->depositPath(
            $folderName,
            $contll->currentPlatformEloq->platform_id,
            $contll->currentPlatformEloq->platform_name,
        ) . '/ico';
        $icoArr = $imageObj->uploadImg($inputDatas['payment_ico'], $depositPath);
        try {
            $inputDatas['payment_ico'] = '/' . $icoArr['path'];
            //执行添加
            $pastDataEloq = new $this->model();
            $pastDataEloq->fill($inputDatas);
            //删除原图
            $imageObj->deletePic(substr($pastDataEloq['payment_ico'], 1));
            //执行添加操作
            $pastDataEloq->save();
            return $contll->msgOut(true);
        } catch (Exception $e) {
            //删除上传成功的图片
            $imageObj->deletePic($icoArr['path']);
            return $contll->msgOut(false, [], $e->getCode(), $e->getMessage());
        }
    }
}
