<?php
/**
 * @Author: Fish
 * @Date:   2019/7/4 19:26
 */

namespace App\Http\SingleActions\Backend\Admin\Help;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Models\User\Supports\FrontendUsersHelpCenter;
use Illuminate\Http\JsonResponse;

class HelpCenterDeleteAction
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
     * 删除
     * @param  BackEndApiMainController  $contll
     * @param  array                     $input
     * @return JsonResponse
     */
    public function execute(BackEndApiMainController $contll, array $input): JsonResponse
    {
        $this->model::find($input['id'])->delete();
        if ($this->model->errors()->messages()) {
            return $contll->msgOut(false);
        }
        return $contll->msgOut(true);
    }
}
