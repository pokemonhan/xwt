<?php

namespace App\Http\SingleActions\Backend\Admin;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Lib\Configure;
use App\Models\Admin\SystemConfiguration;
use Illuminate\Http\JsonResponse;

class ConfiguresEditAction
{
    protected $model;

    /**
     * @param  SystemConfiguration  $systemConfiguration
     */
    public function __construct(SystemConfiguration $systemConfiguration)
    {
        $this->model = $systemConfiguration;
    }

    /**
     * 修改配置
     * @param  BackEndApiMainController  $contll
     * @param  array                     $inputDatas
     * @return JsonResponse
     */
    public function execute(BackEndApiMainController $contll, array $inputDatas): JsonResponse
    {
        $configureEloq = $this->model::find($inputDatas['id']);
        $contll->editAssignment($configureEloq, $inputDatas);
        $configureEloq->save();
        if ($configureEloq->errors()->messages()) {
            return $contll->msgOut(false, [], '', $configureEloq->errors()->messages());
        }
        configure::set($configureEloq->sign, $configureEloq->value); //更新配置缓存
        return $contll->msgOut(true);
    }
}
