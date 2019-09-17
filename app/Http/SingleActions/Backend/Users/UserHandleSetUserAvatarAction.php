<?php

namespace App\Http\SingleActions\Backend\Users;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Models\User\FrontendUser;
use App\Models\User\UserPublicAvatar;
use Illuminate\Http\JsonResponse;

class UserHandleSetUserAvatarAction
{
    protected $model;

    /**
     * @param  FrontendUser  $frontendUser
     */
    public function __construct(FrontendUser $frontendUser)
    {
        $this->model = $frontendUser;
    }
    /**
     * 设定用户头像表
     * @param  BackEndApiMainController  $contll
     * @param  array $inputDatas
     * @return JsonResponse
     */
    public function execute(BackEndApiMainController $contll, array $inputDatas): JsonResponse
    {
        $avatarPic = UserPublicAvatar::getAvatar($inputDatas['avatar_id']);

        if (is_null($avatarPic)) {
            return $contll->msgOut(false, [], '100108');
        }

        $user = FrontendUser::find($inputDatas['user_id']);

        if (!$user) {
            return $contll->msgOut(false, [], '100004');
        }

        $user->pic_path = $avatarPic->pic_path;
        $res = $user->save();
        if (!$res) {
            return $contll->msgOut(false, [], '10019');
        }

        return $contll->msgOut(true);
    }
}
