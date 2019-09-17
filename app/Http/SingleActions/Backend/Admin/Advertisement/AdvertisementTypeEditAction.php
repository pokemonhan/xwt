<?php

namespace App\Http\SingleActions\Backend\Admin\Advertisement;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Models\Advertisement\FrontendSystemAdsType;
use Exception;
use Illuminate\Http\JsonResponse;

class AdvertisementTypeEditAction
{
    protected $model;

    /**
     * @param  FrontendSystemAdsType  $frontendSystemAdsType
     */
    public function __construct(FrontendSystemAdsType $frontendSystemAdsType)
    {
        $this->model = $frontendSystemAdsType;
    }

    /**
     * 编辑广告类型
     * @param  BackEndApiMainController  $contll
     * @param  array $inputDatas
     * @return JsonResponse
     */
    public function execute(BackEndApiMainController $contll, array $inputDatas): JsonResponse
    {
        $editData = $this->model::find($inputDatas['id']);
        $contll->editAssignment($editData, $inputDatas);
        try {
            $editData->save();
            return $contll->msgOut(true);
        } catch (Exception $e) {
            return $contll->msgOut(false, [], $e->getCode(), $e->getMessage());
        }
    }
}
