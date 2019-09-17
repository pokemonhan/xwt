<?php

namespace App\Http\SingleActions\Backend\Admin;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Lib\Configure;
use App\Models\Admin\SystemConfiguration;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class ConfiguresGenerateIssueTimeAction
{
    protected $model;
    protected $sign = 'generate_issue_time';

    /**
     * @param  SystemConfiguration  $systemConfiguration
     */
    public function __construct(SystemConfiguration $systemConfiguration)
    {
        $this->model = $systemConfiguration;
    }

    /**
     * 配置获取奖期时间
     * @param  BackEndApiMainController  $contll
     * @param  array                     $inputDatas
     * @return JsonResponse
     */
    public function execute(BackEndApiMainController $contll, array $inputDatas): JsonResponse
    {
        $pastDataEloq = $this->model::where('sign', $this->sign)->first();
        if ($pastDataEloq === null) {
            $bool = $this->createIssueConfigure($contll, $inputDatas['value']);
            if ($bool === false) {
                return $contll->msgOut(false, [], '100702');
            }
        }
        Configure::set($this->sign, $inputDatas['value']);
        return $contll->msgOut(true);
    }

    /**
     * 创建生成奖期时间的配置
     * @param  BackEndApiMainController $contll
     * @param  string                   $time
     * @return bool
     */
    public function createIssueConfigure(BackEndApiMainController $contll, string $time): bool
    {
        DB::beginTransaction();
        try {
            //生成父级 奖期相关 系统配置
            $addData = [
                'parent_id' => 0,
                'pid' => 1,
                'sign' => 'issue',
                'name' => '奖期相关',
                'description' => '奖期相关的所有配置',
                'add_admin_id' => $contll->partnerAdmin->id,
                'last_update_admin_id' => $contll->partnerAdmin->id,
                'status' => 1,
                'display' => 0,
            ];
            $configureEloq = new $this->model();
            $configureEloq->fill($addData);
            $configureEloq->save();
            //生成子级 生成奖期时间 系统配置
            $data = [
                'parent_id' => $configureEloq->id,
                'pid' => 1,
                'sign' => $this->sign,
                'name' => '生成奖期时间',
                'description' => '每天自动生成奖期的时间',
                'value' => $time,
                'add_admin_id' => $contll->partnerAdmin->id,
                'last_update_admin_id' => $contll->partnerAdmin->id,
                'status' => 1,
                'display' => 0,
            ];
            $issueTimeEloq = new $this->model();
            $issueTimeEloq->fill($data);
            $issueTimeEloq->save();
            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollback();
            return false;
        }
    }
}
