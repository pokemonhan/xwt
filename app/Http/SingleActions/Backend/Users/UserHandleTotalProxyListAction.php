<?php

namespace App\Http\SingleActions\Backend\Users;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Models\User\FrontendUser;
use Illuminate\Http\JsonResponse;

/**
 * Class UserHandleTotalProxyListAction
 * @package App\Http\SingleActions\Backend\Users
 */
class UserHandleTotalProxyListAction
{
    /**
     * @var FrontendUser $model
     */
    protected $model;

    /**
     * UserHandleTotalProxyListAction constructor
     * @param FrontendUser $frontendUser FrontendUser.
     */
    public function __construct(FrontendUser $frontendUser)
    {
        $this->model = $frontendUser;
    }
    /**
     * 获取总代 代理
     * @param BackEndApiMainController $contll BackEndApiMainController.
     * @return JsonResponse
     */
    public function execute(BackEndApiMainController $contll): JsonResponse
    {
        $type = [1,2]; //1总代、2代理
        $data = $this->model->select('id', 'username')
            ->whereIn('type', $type)
            ->get()
            ->makeHidden(['specific','account','team_balance'])
            ->toArray();
        return $contll->msgOut(true, $data);
    }
}
