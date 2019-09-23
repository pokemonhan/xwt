<?php

namespace App\Http\SingleActions\Frontend;

use App\Http\Controllers\FrontendApi\FrontendApiMainController;
use App\Models\User\UserPublicAvatar;
use Illuminate\Http\JsonResponse;

/**
 * Class FrontendAuthUserAvatarsListAction
 * @package App\Http\SingleActions\Frontend
 */
class FrontendAuthUserAvatarsListAction
{
    /**
     * 用户头像列表
     * @param FrontendApiMainController $contll FrontendApiMainController.
     * @return JsonResponse
     */
    public function execute(FrontendApiMainController $contll): JsonResponse
    {
        $userAvatars = new UserPublicAvatar();
        $data = $userAvatars->select('id', 'pic_path')->take(30)->get()->toArray();
        return $contll->msgOut(true, $data);
    }
}
