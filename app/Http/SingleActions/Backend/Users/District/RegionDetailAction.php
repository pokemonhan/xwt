<?php

namespace App\Http\SingleActions\Backend\Users\District;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Models\User\UsersRegion;
use Illuminate\Http\JsonResponse;

class RegionDetailAction
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
     * 获取 省-市-县 列表
     * @param  BackEndApiMainController  $contll
     * @return JsonResponse
     */
    public function execute(BackEndApiMainController $contll): JsonResponse
    {
        $datas = $this->model::whereIn('region_level', [1, 2, 3])->get()->toArray();
        return $contll->msgOut(true, $datas);
    }
}
