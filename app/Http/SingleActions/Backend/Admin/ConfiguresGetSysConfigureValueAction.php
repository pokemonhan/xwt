<?php

namespace App\Http\SingleActions\Backend\Admin;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Models\Admin\SystemConfiguration;
use Illuminate\Http\JsonResponse;

class ConfiguresGetSysConfigureValueAction
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
     * 获取某个配置的值
     * @param  BackEndApiMainController  $contll
     * @param  array                     $inputDatas
     * @return JsonResponse
     */
    public function execute(BackEndApiMainController $contll, array $inputDatas): JsonResponse
    {
        $sysConfiguresEloq = new $this->model;
        $time = $sysConfiguresEloq->getConfigValue($inputDatas['key']);
        return $contll->msgOut(true, $time);
    }
}
