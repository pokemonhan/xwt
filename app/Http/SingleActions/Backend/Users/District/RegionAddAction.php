<?php

namespace App\Http\SingleActions\Backend\Users\District;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Models\User\UsersRegion;
use Illuminate\Http\JsonResponse;

class RegionAddAction
{
    protected $model;

    /**
     * @param  UsersRegion  $usersRegion
     */
    public function __construct(UsersRegion $usersRegion)
    {
        $this->model = $usersRegion;
    }

    /**
     * 添加行政区
     * @param  BackEndApiMainController  $contll
     * @param  array $inputDatas
     * @return JsonResponse
     */
    public function execute(BackEndApiMainController $contll, array $inputDatas): JsonResponse
    {
        $isExist = $this->model::where([
            'region_parent_id' => $inputDatas['region_parent_id'],
            'region_name' => $inputDatas['region_name'],
        ])->orwhere('region_id', $inputDatas['region_id'])->exists();
        if ($isExist === true) {
            return $contll->msgOut(false, [], '101001');
        }
        $configureEloq = new $this->model();
        $configureEloq->fill($inputDatas);
        $configureEloq->save();
        if ($configureEloq->errors()->messages()) {
            return $contll->msgOut(false, [], '', $configureEloq->errors()->messages());
        }
        return $contll->msgOut(true);
    }
}
