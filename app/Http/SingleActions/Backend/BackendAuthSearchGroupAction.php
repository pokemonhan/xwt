<?php

namespace App\Http\SingleActions\Backend;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Models\Admin\BackendAdminAccessGroup;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class BackendAuthSearchGroupAction
{
    protected $model;

    /**
     * @param  BackendAdminAccessGroup $backendAdminAccessGroup
     */
    public function __construct(BackendAdminAccessGroup $backendAdminAccessGroup)
    {
        $this->model = $backendAdminAccessGroup;
    }

    /**
     * @param  BackEndApiMainController $contll
     * @param  array                    $inputDatas
     * @return JsonResponse
     */
    public function execute(BackEndApiMainController $contll, array $inputDatas): JsonResponse
    {
        if ($inputDatas['group_id'] === '-1') {
            $targetUserEloq = $this->model->get();
        } else {
            $targetUserEloq = $this->model::where([
                ['id', '=', $inputDatas['group_id']],
            ])->first();
        }
        if ($targetUserEloq === null) {
            return $contll->msgOut(false, [], '100004');
        }
        return $contll->msgOut(true, $targetUserEloq);
    }
}
