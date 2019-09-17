<?php

namespace App\Http\SingleActions\Frontend\Homepage;

use App\Http\Controllers\FrontendApi\FrontendApiMainController;
use App\Models\Admin\SystemConfiguration;
use Illuminate\Http\JsonResponse;

class HomepageGetWebInfoAction
{
    /**
     * 获取网站基本信息
     * @param  FrontendApiMainController $contll
     * @return JsonResponse
     */
    public function execute(FrontendApiMainController $contll): JsonResponse
    {
        $data = SystemConfiguration::getWebInfo();
        return $contll->msgOut(true, $data);
    }
}
