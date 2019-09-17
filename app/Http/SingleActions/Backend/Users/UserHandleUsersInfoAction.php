<?php

namespace App\Http\SingleActions\Backend\Users;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Models\User\FrontendUser;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class UserHandleUsersInfoAction
{
    protected $model;

    /**
     * @param  FrontendUser $frontendUser
     */
    public function __construct(FrontendUser $frontendUser)
    {
        $this->model = $frontendUser;
    }

    /**
     * 用户管理的所有用户信息表
     * @param  BackEndApiMainController $contll
     * @return JsonResponse
     */
    public function execute(BackEndApiMainController $contll): JsonResponse
    {
        //target model to join
        $fixedJoin = 1; //number of joining tables
        $withTable = 'account';
        $searchAbleFields = [
            'username',
            'type',
            'vip_level',
            'is_tester',
            'frozen_type',
            'prize_group',
            'level_deep',
            'register_ip',
            'parent_id',
        ];
        $withSearchAbleFields = ['balance'];
        $data = $contll->generateSearchQuery(
            $this->model,
            $searchAbleFields,
            $fixedJoin,
            $withTable,
            $withSearchAbleFields
        );
        $isSetTotal = isset($contll->inputs['total_members']) ? true : false;
        $isSetParentName = isset($contll->inputs['parent_name']) ? true : false;
        $isSetParentId = isset($contll->inputs['parent_id']) ? true : false;
        $isSetParentInfo = isset($contll->inputs['parent_info']) ? true : false;

        if ($isSetParentName === true) {
            self::getParentName($data);
        }

        if ($isSetTotal === true) {
            self::getTotal($data);
        }
        if ($isSetParentId === true && $isSetParentInfo === true) {
            self::addParentInfo($data, $contll);
        }
        return $contll->msgOut(true, $data);
    }

    private function addParentInfo(&$data, $contll)
    {
        $parentInfo = $this->model->find($contll->inputs['parent_id'])->toArray();
        unset($parentInfo['password']);
        unset($parentInfo['fund_password']);
        $data['parentInfo'] = $parentInfo;
    }

    private function getParentName(&$data)
    {
        $totalData = $this->model->get()->toArray();
        $totalData = array_combine(array_column($totalData, 'id'), $totalData);
        foreach ($data as $dataKey => $totalValue) {
            $parentName = self::getParentUserName($totalData, $totalValue);
            if ($parentName['status'] === false) {
                //    return $contll->msgOut(false, $parentName['data'], '100110');
                $data[$dataKey]['parent_username'] = '该用户上级不存在';
            }
            $data[$dataKey]['parent_username'] = $parentName['data'];
        }
    }

    private function getTotal(&$data)
    {
        $dataChange = $data->toArray()['data'];
        $dataChange = array_combine(array_column($dataChange, 'id'), $dataChange);
        $dataChange = array_keys($dataChange);
        $specData = DB::table('frontend_users_specific_infos')
            ->whereIn('user_id', $dataChange)
            ->get()->toArray();
        $specData = array_combine(array_column($specData, 'user_id'), $specData);
        foreach ($data as $dataKey => $totalValue) {
            $data[$dataKey]['total_members'] = isset($specData[$totalValue->id]) ? $specData[$totalValue->id]->total_members : 0;
        }
    }


    /**
     * 将用户的的父ID变为用户名
     * @param  mixed $totalData
     * @param  mixed $parentKeys
     * @return mixed
     */
    public function getParentUserName($totalData, $parentKeys)
    {
        $resp = array(
            'status' => true,
            'data' => ''
        );
        $arrData = explode('|', trim($parentKeys['rid'], '|'));
        $parentUserName = '';
        if (count($arrData) < 1) {
            return $resp;
        }
        foreach ($arrData as $arrDataV) {
            if (!isset($totalData[$arrDataV])) {
                $resp['status'] = false;
                $resp['data'] = $parentKeys;
                $resp['error'] = '该用户的上级ID不存在,请联系管理员';
                return $resp;
            }
            $parentUserName .= ',' . $totalData[$arrDataV]['username'];
        }
        $resp['data'] = trim($parentUserName, ',');
        return $resp;
    }
}
