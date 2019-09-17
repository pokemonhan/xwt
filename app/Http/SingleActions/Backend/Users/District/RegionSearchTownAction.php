<?php

namespace App\Http\SingleActions\Backend\Users\District;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Models\User\UsersRegion;
use Illuminate\Http\JsonResponse;

class RegionSearchTownAction
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
     * 模糊搜索 镇(街道)
     * @param  BackEndApiMainController  $contll
     * @param  array $inputDatas
     * @return JsonResponse
     */
    public function execute(BackEndApiMainController $contll, array $inputDatas): JsonResponse
    {
        $datas = $this->model::select(
            'a.*',
            'b.region_name as country_name',
            'c.region_name as city_name',
            'd.region_name as province_name'
        )
            ->from('users_regions as a')
            ->leftJoin('users_regions as b', 'a.region_parent_id', '=', 'b.region_id')
            ->leftJoin('users_regions as c', 'b.region_parent_id', '=', 'c.region_id')
            ->leftJoin('users_regions as d', 'c.region_parent_id', '=', 'd.region_id')
            ->where([['a.region_name', 'like', '%' . $inputDatas['search_name'] . '%'], ['a.region_level', 4]])
            ->get()->toArray();
        return $contll->msgOut(true, $datas);
    }
}
