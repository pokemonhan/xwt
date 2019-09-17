<?php

namespace App\Http\Controllers\BackendApi\Admin;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Http\Requests\Backend\Admin\ConfiguresAddRequest;
use App\Http\Requests\Backend\Admin\ConfiguresConfigSwitchRequest;
use App\Http\Requests\Backend\Admin\ConfiguresDeleteRequest;
use App\Http\Requests\Backend\Admin\ConfiguresEditRequest;
use App\Http\Requests\Backend\Admin\ConfiguresGenerateIssueTimeRequest;
use App\Http\Requests\Backend\Admin\ConfiguresGetSysConfigureValueRequest;
use App\Http\SingleActions\Backend\Admin\ConfiguresAddAction;
use App\Http\SingleActions\Backend\Admin\ConfiguresConfigSwitchAction;
use App\Http\SingleActions\Backend\Admin\ConfiguresCreateIssueConfigureAction;
use App\Http\SingleActions\Backend\Admin\ConfiguresDeleteAction;
use App\Http\SingleActions\Backend\Admin\ConfiguresEditAction;
use App\Http\SingleActions\Backend\Admin\ConfiguresGenerateIssueTimeAction;
use App\Http\SingleActions\Backend\Admin\ConfiguresGetConfiguresListAction;
use App\Http\SingleActions\Backend\Admin\ConfiguresGetSysConfigureValueAction;
use Illuminate\Http\JsonResponse;

class ConfiguresController extends BackEndApiMainController
{
    /**
     * 获取全部配置
     * @param  ConfiguresGetConfiguresListAction $action
     * @return JsonResponse
     */
    public function getConfiguresList(ConfiguresGetConfiguresListAction $action): JsonResponse
    {
        return $action->execute($this);
    }

    /**
     * 添加配置
     * @param  ConfiguresAddRequest $request
     * @param  ConfiguresAddAction  $action
     * @return JsonResponse
     */
    public function add(ConfiguresAddRequest $request, ConfiguresAddAction $action): JsonResponse
    {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }

    /**
     * 修改配置
     * @param  ConfiguresEditRequest $request
     * @param  ConfiguresEditAction  $action
     * @return JsonResponse
     */
    public function edit(ConfiguresEditRequest $request, ConfiguresEditAction $action): JsonResponse
    {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }

    /**
     * 删除配置
     * @param  ConfiguresDeleteRequest $request
     * @param  ConfiguresDeleteAction  $action
     * @return JsonResponse
     */
    public function delete(ConfiguresDeleteRequest $request, ConfiguresDeleteAction $action): JsonResponse
    {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }

    /**
     * 配置状态开关 0关  1开
     * @param  ConfiguresConfigSwitchRequest $request
     * @param  ConfiguresConfigSwitchAction  $action
     * @return JsonResponse
     */
    public function configSwitch(
        ConfiguresConfigSwitchRequest $request,
        ConfiguresConfigSwitchAction $action
    ): JsonResponse {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }

    /**
     * 配置获取奖期时间
     * @param  ConfiguresGenerateIssueTimeRequest $request
     * @param  ConfiguresGenerateIssueTimeAction  $action
     * @return JsonResponse
     */
    public function generateIssueTime(
        ConfiguresGenerateIssueTimeRequest $request,
        ConfiguresGenerateIssueTimeAction $action
    ): JsonResponse {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }

    /**
     * 获取某个配置的值
     * @param  ConfiguresGetSysConfigureValueRequest $request
     * @param  ConfiguresGetSysConfigureValueAction  $action
     * @return JsonResponse
     */
    public function getSysConfigureValue(
        ConfiguresGetSysConfigureValueRequest $request,
        ConfiguresGetSysConfigureValueAction $action
    ): JsonResponse {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }
}
