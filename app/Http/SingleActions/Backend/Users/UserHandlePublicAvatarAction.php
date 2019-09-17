<?php

namespace App\Http\SingleActions\Backend\Users;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Models\User\UserPublicAvatar;
use Illuminate\Http\JsonResponse;

class UserHandlePublicAvatarAction
{
    protected $model;

    /**
     * @param  UserPublicAvatar  $userPublicAvatar
     */
    public function __construct(UserPublicAvatar $userPublicAvatar)
    {
        $this->model = $userPublicAvatar;
    }
    /**
     * 获取公共头像列表
     * @param  BackEndApiMainController  $contll
     * @return JsonResponse
     */
    public function execute(BackEndApiMainController $contll): JsonResponse
    {
        $searchAbleFields = ['id', 'pic_path', 'created_at'];
        $data = $contll->generateSearchQuery($this->model, $searchAbleFields);
        return $contll->msgOut(true, $data);
    }
}
