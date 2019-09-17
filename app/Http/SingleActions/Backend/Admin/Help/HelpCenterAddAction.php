<?php
/**
 * @Author: Fish
 * @Date:   2019/7/4 17:47
 */

namespace App\Http\SingleActions\Backend\Admin\Help;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Models\User\Supports\FrontendUsersHelpCenter;
use Illuminate\Http\JsonResponse;

class HelpCenterAddAction
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
     * 添加
     * @param  BackEndApiMainController  $contll
     * @param  array                     $input
     * @return JsonResponse
     */
    public function execute(BackEndApiMainController $contll, array $input): JsonResponse
    {
        $this->model->fill($input);
        $this->model->save();
        if ($this->model->errors()->messages()) {
            return $contll->msgOut(false);
        }
        return $contll->msgOut(true);
    }
}
