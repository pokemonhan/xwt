<?php

namespace App\Http\SingleActions\Backend\Admin\Log;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Models\Admin\FrontendSystemLog;
use Illuminate\Http\JsonResponse;

class HandleLogFrontendLogsAction
{
    /**
     * 前台日志列表
     * @param  BackEndApiMainController  $contll
     * @return JsonResponse
     */
    public function execute(BackEndApiMainController $contll): JsonResponse
    {
        $logEloq = new FrontendSystemLog();
        $searchAbleFields = [
            'origin',
            'ip',
            'device',
            'os',
            'os_version',
            'browser',
            'username',
            'menu_label',
            'device_type'
        ];
        $data = $contll->generateSearchQuery($logEloq, $searchAbleFields);
        return $contll->msgOut(true, $data);
    }
}
