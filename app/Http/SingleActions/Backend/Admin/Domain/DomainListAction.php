<?php

namespace App\Http\SingleActions\Backend\Admin\Domain;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Models\Admin\Domain\BackendDomain;
use App\Models\Admin\BackendAdminAccessGroup;
use Illuminate\Http\JsonResponse;

class DomainListAction
{
    protected $model;

    /**
     * @param  BackendDomain $backendDomain
     */
    public function __construct(BackendDomain $backendDomain)
    {
        $this->model = $backendDomain;
    }

    /**
     * 域名列表
     * @param  BackEndApiMainController $contll
     * @param  mixed $inputDatas
     * @return JsonResponse
     */
    public function execute(BackEndApiMainController $contll, $inputDatas): JsonResponse
    {
        $groupId = $contll->partnerAdmin['group_id'];
        $roleFlag = BackendAdminAccessGroup::find($groupId);

        if ($roleFlag->role !== '*') {
            $userId = $contll->partnerAdmin['id'];
            $data = $this->model->where('user_id', $userId)->get();
            return $contll->msgOut(true, $data);
        }


        $userId = isset($inputDatas['user_id']) ? $inputDatas['user_id'] : false;
        if ($userId == false) {
            $searchAbleFields = ['id', 'user_id', 'domain', 'config_id', 'is_use', 'created_at', 'updated_at'];
            $data = $contll->generateSearchQuery($this->model, $searchAbleFields);
        } else {
            $data = $this->model->where('user_id', $userId)->get();
        }
        return $contll->msgOut(true, $data);
    }
}
