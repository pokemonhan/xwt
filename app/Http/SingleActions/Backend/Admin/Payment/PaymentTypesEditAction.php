<?php

namespace App\Http\SingleActions\Backend\Admin\Payment;

use App\Http\Controllers\BackendApi\Admin\Payment\PaymentTypesController;
use App\Lib\Common\ImageArrange;
use App\Models\Admin\Payment\BackendPaymentConfig;
use App\Models\Admin\Payment\BackendPaymentType;
use Illuminate\Http\JsonResponse;
use App\Lib\BaseCache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;

/**
 * Class PaymentTypesEditAction
 * @package App\Http\SingleActions\Backend\Admin\Payment
 */
class PaymentTypesEditAction
{
    use BaseCache;

    /**
     * @var BackendPaymentType $model BackendPaymentType.
     */
    protected $model;

    /**
     * PaymentTypesEditAction constructor.
     * @param BackendPaymentType $backendPaymentType BackendPaymentType.
     */
    public function __construct(BackendPaymentType $backendPaymentType)
    {
        $this->model = $backendPaymentType;
    }

    /**
     * 编辑支付方式类型表
     * @param PaymentTypesController $contll     PaymentTypesController.
     * @param array                  $inputDatas InputDatas.
     * @return JsonResponse
     * @throws \Exception 异常.
     */
    public function execute(PaymentTypesController $contll, array $inputDatas): JsonResponse
    {
        DB::beginTransaction();
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
                //获取原来数据库图标信息
                $inputDatas['payment_ico'] = BackendPaymentType::select('payment_ico')->find($inputDatas['id']);
            }
            //判断插入数据库是否有重复
            if (!empty($inputDatas['payment_type_name']) && !empty($inputDatas['payment_type_sign']) && isset($inputDatas['payment_type_name'], $inputDatas['payment_type_sign'])) {
                $pastDataEloq = BackendPaymentType::where([['payment_type_name','=',$inputDatas['payment_type_name']],['payment_type_sign','=',$inputDatas['payment_type_sign']]])->first();
                if ($pastDataEloq) {
                    return $contll->msgOut(false, [], '102700');
                }
            }
            //执行修改
            $pastDataEloq = BackendPaymentType::find($inputDatas['id']);
            $pastDataEloq->fill($inputDatas);
            $pastDataEloq->save();
            //执行修改错误操作
            if ($pastDataEloq->errors()->messages()) {
                $this->deletePic($imageObj, $inputDatas['payment_ico']);
                return $contll->msgOut(false, [], '400', $pastDataEloq->errors()->messages());
            }
            $this->deletePicNoFinsh($imageObj, $inputDatas['payment_ico']);
            //更新支付配置表信息
            $this->updateConfig($inputDatas, $pastDataEloq);
            DB::commit();
            return $contll->msgOut(true);
        } catch (Exception $e) {
            $this->deletePic($imageObj, $inputDatas['payment_ico']);
            Log::channel('dy-activity')->info($e->getMessage());
            DB::rollBack();
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

    /**
     * 更新支付配置表信息
     * @param mixed $inputDatas   InputDatas.
     * @param mixed $pastDataEloq PastDataEloq.
     * @return void
     */
    private function updateConfig($inputDatas, $pastDataEloq)
    {
        $editDatas = [];
        $editDatas['banks_code'] = [
            'name' => $pastDataEloq->payment_type_name,
            'ico' => $pastDataEloq->payment_ico,
            'code' => $pastDataEloq->is_bank,
        ];
        $editDatas['banks_code'] = json_encode($editDatas['banks_code']);
        $editDatas['payment_type_name'] = $pastDataEloq->payment_type_name;
        $editDatas['payment_type_sign'] = $pastDataEloq->payment_type_sign;
        BackendPaymentConfig::where('payment_type_id', $inputDatas['id'])->update($editDatas);
    }
}
