<?php

namespace App\Http\SingleActions\Backend\Admin\Payment;

use App\Http\Controllers\BackendApi\Admin\Payment\PaymentTypesController;
use App\Lib\Common\ImageArrange;
use App\Models\Admin\Payment\BackendPaymentType;
use Illuminate\Http\JsonResponse;
use App\Lib\BaseCache;
use Illuminate\Support\Facades\Log;
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
        $previewIco = []; //保存图片的变量
        $imageObj = new ImageArrange();
        try {
            //上传图标
            if (isset($inputDatas['payment_ico'])) {
                $previewIco = $this->savePic($imageObj, 'payment_ico', $contll, $inputDatas['payment_ico']);
                if ($previewIco['success'] === false) {
                    return $contll->msgOut(false, [], '400', $previewIco['msg']);
                }
                $inputDatas['payment_ico'] = '/' . $previewIco['path'];
            } else {
                return $contll->msgOut(false, [], '102600');
            }
            //执行添加
            $pastDataEloq = new $this->model();
            $pastDataEloq->fill($inputDatas);
            $pastDataEloq->save();
            //执行错误添加后的操作
            if ($pastDataEloq->errors()->messages()) {
                $this->deletePic($imageObj, $inputDatas['payment_ico']);
                return $contll->msgOut(false, [], '400', $pastDataEloq->errors()->messages());
            }
            $this->deletePicNoFinsh($imageObj, $inputDatas['payment_ico']);
            return $contll->msgOut(true);
        } catch (Exception $e) {
            $this->deletePic($imageObj, $inputDatas['payment_ico']);
            Log::channel('dy-activity')->info($e->getMessage());
            return $contll->msgOut(false, [], '400', '系统异常');
        }
    }

    /**
     * 保存图片
     * @param mixed $imageObj  ImageObj.
     * @param mixed $path      Path.
     * @param mixed $contll    Contll.
     * @param mixed $picSource PicSource.
     * @return mixed
     */
    private function savePic($imageObj, $path, $contll, $picSource)
    {
        $picSavePath = $imageObj->depositPath($contll->currentPlatformEloq->platform_name . '/' . $path, $contll->currentPlatformEloq->platform_id, $contll->currentPlatformEloq->platform_name);
        $previewPic = $imageObj->uploadImg($picSource, $picSavePath);
        return $previewPic;
    }

    /**
     * 如果上传失败删除图片
     * @param mixed $imageObj   ImageObj.
     * @param mixed $previewIco PreviewIco.
     * @return void
     */
    private function deletePic($imageObj, $previewIco)
    {
        if (isset($previewIco['payment_ico'])) {//上传失败   删除前面上传的图片
            $imageObj->deletePic($previewIco['payment_ico']);
        }
    }

    /**
     * @param mixed $imageObj   ImageObj.
     * @param mixed $previewIco PreviewIco.
     * @return void
     */
    private function deletePicNoFinsh($imageObj, $previewIco)
    {
        if (isset($previewIco) && !empty($previewIco)) {
            $imageObj->deletePic(substr($previewIco, 1));
        }
    }
}
