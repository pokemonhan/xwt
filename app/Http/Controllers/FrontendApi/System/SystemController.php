<?php

namespace App\Http\Controllers\FrontendApi\System;

use App\Http\Controllers\FrontendApi\FrontendApiMainController;

class SystemController extends FrontendApiMainController
{
    //是否加密数据
    public function isCryptData()
    {
        $isCryptData = configure('is_crypt_data');
        return $this->msgOut(true, (bool) $isCryptData);
    }
}
