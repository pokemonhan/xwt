<?php

namespace App\Http\SingleActions\Backend\Admin;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Lib\Configure;
use App\Models\Admin\SystemConfiguration;
use Illuminate\Http\JsonResponse;

class ConfiguresAddAction
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
     * 添加配置
     * @param  BackEndApiMainController  $contll
     * @param  array                     $inputDatas
     * @return JsonResponse
     */
    public function execute(BackEndApiMainController $contll, array $inputDatas): JsonResponse
    {
        $addDatas = $inputDatas;
        $addDatas['pid'] = $contll->currentPlatformEloq->platform_id;
        $addDatas['add_admin_id'] = $contll->partnerAdmin->id;
        $addDatas['last_update_admin_id'] = $contll->partnerAdmin->id;
        $addDatas['status'] = 1;
        $configure = new $this->model();
        $configure->fill($addDatas);
        $configure->save();
        if ($configure->errors()->messages()) {
            return $contll->msgOut(false, [], '', $configure->errors()->messages());
        }
        Configure::set($configure->sign, $configure->value); //更新配置缓存
        return $contll->msgOut(true);
    }
}
