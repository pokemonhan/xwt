<?php
/**
 * @Author: Fish
 * @Date:   2019/7/4 16:02
 */

namespace App\Http\SingleActions\Backend\Admin\Help;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Models\User\Supports\FrontendUsersHelpCenter;
use Illuminate\Http\JsonResponse;

class HelpCenterDetailAction
{
    protected $model;

    /**
     * @param  FrontendUsersHelpCenter  $frontendUsersHelpCenter
     */
    public function __construct(FrontendUsersHelpCenter $frontendUsersHelpCenter)
    {
        $this->model = $frontendUsersHelpCenter;
    }

    /**
     * 帮助中心列表
     * @param  BackEndApiMainController  $contll
     * @return JsonResponse
     */
    public function execute(BackEndApiMainController $contll): JsonResponse
    {
        $menu = $this->model->getHelpCenterData();
        return $contll->msgOut(true, $menu);
    }
}
