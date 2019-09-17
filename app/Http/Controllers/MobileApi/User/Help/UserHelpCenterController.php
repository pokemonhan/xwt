<?php

namespace App\Http\Controllers\MobileApi\User\Help;

use App\Http\Controllers\FrontendApi\FrontendApiMainController;
use App\Http\SingleActions\Frontend\User\Help\UserHelpCenterAction;
use Illuminate\Http\JsonResponse;

class UserHelpCenterController extends FrontendApiMainController
{
    /**
     * 帮助中心菜单
     * @param  UserHelpCenterAction $action
     * @return JsonResponse
     */
    public function menus(UserHelpCenterAction $action): JsonResponse
    {
        return $action->execute($this);
    }
}
