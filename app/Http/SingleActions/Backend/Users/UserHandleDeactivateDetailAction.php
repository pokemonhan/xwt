<?php

namespace App\Http\SingleActions\Backend\Users;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Models\Admin\FrontendUsersPrivacyFlow;
use App\Models\User\FrontendUser;
use Illuminate\Http\JsonResponse;

class UserHandleDeactivateDetailAction
{
    protected $model;

    /**
     * @param  FrontendUser  $frontendUser
     */
    public function __construct(FrontendUser $frontendUser)
    {
        $this->model = $frontendUser;
    }

    /**
     * 用户冻结记录
     * @param  BackEndApiMainController  $contll
     * @param  array $inputDatas
     * @return JsonResponse
     */
    public function execute(BackEndApiMainController $contll, array $inputDatas): JsonResponse
    {
        $userEloq = $this->model::find($inputDatas['user_id']);
        if ($userEloq !== null) {
            $data = FrontendUsersPrivacyFlow::where('user_id', $inputDatas['user_id'])
                ->whereBetween('created_at', [$inputDatas['start_time'], $inputDatas['end_time']])
                ->orderBy('created_at', 'desc')
                ->get()
                ->toArray();
            return $contll->msgOut(true, $data);
        }
    }
}
