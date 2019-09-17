<?php

namespace App\Http\SingleActions\Backend\Admin\Advertisement;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Models\Advertisement\FrontendSystemAdsType;
use Illuminate\Http\JsonResponse;

class AdvertisementTypeDetailAction
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
     * 广告列表
     * @param  BackEndApiMainController  $contll
     * @return JsonResponse
     */
    public function execute(BackEndApiMainController $contll): JsonResponse
    {
        $datas = $this->model::select(
            'id',
            'name',
            'type',
            'status',
            'ext_type',
            'l_size',
            'w_size',
            'size'
        )->get()->toArray();
        return $contll->msgOut(true, $datas);
    }
}
