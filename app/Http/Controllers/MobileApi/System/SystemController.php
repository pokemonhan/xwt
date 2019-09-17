<?php

namespace App\Http\Controllers\MobileApi\System;

use App\Http\Controllers\FrontendApi\FrontendApiMainController;
use Illuminate\Http\JsonResponse;

class SystemController extends FrontendApiMainController
{
    //是否加密数据
    public function isCryptData(): JsonResponse
    {
        $isCryptData = configure('is_crypt_data');
        return $this->msgOut(true, (bool) $isCryptData);
    }
}
