<?php

namespace App\Http\Controllers\BackendApi\System;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Http\Requests\Backend\Admin\Help\HelpCenterUploadPicRequest;
use App\Http\Requests\Backend\System\SystemUploadPicRequest;
use App\Http\SingleActions\Backend\System\SystemUploadPiclAction;
use Illuminate\Http\JsonResponse;

class SystemController extends BackEndApiMainController
{
    /**
     * 图片上传
     * @param  SystemUploadPicRequest $request
     * @return JsonResponse
     */
    public function uploadPic(SystemUploadPicRequest $request, SystemUploadPiclAction $action): JsonResponse
    {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }
}
