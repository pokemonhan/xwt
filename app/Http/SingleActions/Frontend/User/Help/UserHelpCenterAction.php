<?php
namespace App\Http\SingleActions\Frontend\User\Help;

use App\Http\Controllers\FrontendApi\FrontendApiMainController;
use App\Models\User\Supports\FrontendUsersHelpCenter;
use Illuminate\Http\JsonResponse;

class UserHelpCenterAction
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
     * 帮助中心菜单
     * @param  FrontendApiMainController $contll
     * @return JsonResponse
     */
    public function execute(FrontendApiMainController $contll): JsonResponse
    {
        $menu = $this->model->getHelpCenterData();
        return $contll->msgOut(true, $menu);
    }
}
