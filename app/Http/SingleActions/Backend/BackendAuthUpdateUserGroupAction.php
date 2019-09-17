<?php

namespace App\Http\SingleActions\Backend;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Models\Admin\BackendAdminUser;
use Illuminate\Http\JsonResponse;

class BackendAuthUpdateUserGroupAction
{
    protected $model;

    /**
     * @param  BackendAdminUser  $backendAdminUser
     */
    public function __construct(BackendAdminUser $backendAdminUser)
    {
        $this->model = $backendAdminUser;
    }

    /**
     * 修改管理员的归属组
     * @param  BackEndApiMainController  $contll
     * @param  array                     $inputDatas
     * @return JsonResponse
     */
    public function execute(BackEndApiMainController $contll, array $inputDatas): JsonResponse
    {
        $targetUserEloq = $this->model::find($inputDatas['id']);
        $result = [];
        if ($targetUserEloq !== null) {
            $targetUserEloq->group_id = $inputDatas['group_id'];
            $targetUserEloq->save();
            if ($targetUserEloq->errors()->messages()) {
                return $contll->msgOut(false, [], '', $targetUserEloq->errors()->messages());
            }
            $result = $targetUserEloq->toArray();
        }
        return $contll->msgOut(true, $result);
    }
}
