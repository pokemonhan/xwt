<?php

namespace App\Http\SingleActions\Backend\Users\District;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Models\User\UsersRegion;
use Illuminate\Http\JsonResponse;

class RegionEditAction
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
     * 编辑行政区
     * @param  BackEndApiMainController  $contll
     * @param  array $inputDatas
     * @return JsonResponse
     */
    public function execute(BackEndApiMainController $contll, array $inputDatas): JsonResponse
    {
        $isExist = $this->model::where([
            ['region_id', '=', $inputDatas['region_id']],
            ['id', '!=', $inputDatas['id']],
        ])->exists();
        if ($isExist === true) {
            return $contll->msgOut(false, [], '101001');
        }
        $regionEloq = $this->model::find($inputDatas['id']);
        $regionEloq->region_id = $inputDatas['region_id'];
        $regionEloq->region_name = $inputDatas['region_name'];
        $regionEloq->save();
        if ($regionEloq->errors()->messages()) {
            return $contll->msgOut(false, [], '', $regionEloq->errors()->messages());
        }
        return $contll->msgOut(true);
    }
}
