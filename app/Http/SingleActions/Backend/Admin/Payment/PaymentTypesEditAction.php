<?php

namespace App\Http\SingleActions\Backend\Admin\Payment;

use App\Http\Controllers\BackendApi\Admin\Payment\PaymentTypesController;
use App\Lib\Common\ImageArrange;
use App\Models\Admin\Payment\BackendPaymentConfig;
use App\Models\Admin\Payment\BackendPaymentType;
use Illuminate\Http\JsonResponse;
use App\Lib\BaseCache;
use Illuminate\Support\Facades\DB;
use Exception;

/**
 * Class PaymentTypesEditAction
 * @package App\Http\SingleActions\Backend\Admin\Payment
 */
class PaymentTypesEditAction
{
    use BaseCache;

    /**
     * @var BackendPaymentType $model 支付方式类型表模型.
     */
    protected $model;

    /**
     * PaymentTypesEditAction constructor.
     * @param BackendPaymentType $backendPaymentType 支付方式类型表模型.
     */
    public function __construct(BackendPaymentType $backendPaymentType)
    {
        $this->model = $backendPaymentType;
    }

    /**
     * 编辑支付方式类型表
     * @param PaymentTypesController $contll     支付方式类型表控制器.
     * @param array                  $inputDatas 前端获取参数.
     * @return JsonResponse
     * @throws \Exception 异常.
     */
    public function execute(PaymentTypesController $contll, array $inputDatas): JsonResponse
    {
        DB::beginTransaction();
        $pastDataEloq = BackendPaymentType::find($inputDatas['id']);
        $imageObj = new ImageArrange();
        //判断图片地址是否变化
        if (!empty($inputDatas['payment_ico']) && isset($inputDatas['payment_ico'])) {
            $folderName = 'payment_type';
            $depositPath = $imageObj->depositPath(
                $folderName,
                $contll->currentPlatformEloq->platform_id,
                $contll->currentPlatformEloq->platform_name,
            ) . '/ico';
            $icoArr = $imageObj->uploadImg($inputDatas['payment_ico'], $depositPath);
            $inputDatas['payment_ico'] = '/' . $icoArr['path'];
        }
        try {
            //判断插入数据库是否有重复
            $isExistType = $this->isExistType($inputDatas, $pastDataEloq);
            if (!empty($isExistType) && isset($isExistType)) {
                return $contll->msgOut(false, [], '102700');
            }

            //执行修改
            $pastDataEloq->fill($inputDatas);
            //删除原图
            if (!empty($inputDatas['payment_ico']) && isset($inputDatas['payment_ico'])) {
                $imageObj->deletePic(substr($inputDatas['payment_ico'], 1));
            }
            $pastDataEloq->save();

            //更新支付配置表信息
            $this->updateConfig($inputDatas, $pastDataEloq);
            DB::commit();
            return $contll->msgOut(true);
        } catch (Exception $e) {
            //删除原图
            if (!empty($inputDatas['payment_ico']) && isset($inputDatas['payment_ico'])) {
                $imageObj->deletePic(substr($inputDatas['payment_ico'], 1));
            }
            DB::rollBack();
            return $contll->msgOut(false, [], '102602');
        }
    }

    /**
     * 更新支付配置表信息
     * @param mixed $inputDatas   前端输入的参数.
     * @param mixed $pastDataEloq 根据前端获取的ID查询出支付方式类型表数据.
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
        //更新支付配置表信息
        BackendPaymentConfig::where('payment_type_id', $inputDatas['id'])->update($editDatas);
    }

    /**
     * 判断支付方式类型表数据是否存在
     * @param mixed $inputDatas   前台获取的参数.
     * @param mixed $pastDataEloq 支付方式类型表数据.
     * @return mixed
     */
    private function isExistType($inputDatas, $pastDataEloq)
    {
        //判断修改第三方厂商表的数据是否存在
        if (!empty($inputDatas['payment_type_sign']) && isset($inputDatas['payment_type_sign'])) {
            $array = [
                ['payment_type_name', '=', $pastDataEloq['payment_type_name']],
                ['payment_type_sign', '=', $inputDatas['payment_type_sign']],
            ];
        } elseif (!empty($inputDatas['payment_type_name']) && isset($inputDatas['payment_type_name'])) {
            $array = [
                ['payment_type_name', '=', $inputDatas['payment_type_name']],
                ['payment_type_sign', '=', $pastDataEloq['payment_type_sign']],
            ];
        } elseif (empty($inputDatas['payment_type_name']) &&
            empty($inputDatas['payment_type_sign']) && !isset($inputDatas['payment_type_name']) && !isset($inputDatas['payment_type_sign'])) {
            $array = [];
        } else {
            $array = [
                ['payment_type_name', '=', $inputDatas['payment_type_name']],
                ['payment_type_sign', '=', $inputDatas['payment_type_sign']],
            ];
        }
        if (!empty($array)) {
            return BackendPaymentType::where($array)->first();
        }
    }
}
