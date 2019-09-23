<?php

namespace App\Http\SingleActions\Backend\Users;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Models\User\FrontendUser;
use App\Models\User\FrontendUsersSpecificInfo;
use App\Models\User\Fund\FrontendUsersAccount;
use App\Models\User\UserPublicAvatar;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

/**
 * Class UserHandleCreateUserAction
 * @package App\Http\SingleActions\Backend\Users
 */
class UserHandleCreateUserAction
{
    /**
     * @var FrontendUser
     */
    protected $model;

    /**
     * @param  FrontendUser $frontendUser 用户模型.
     */
    public function __construct(FrontendUser $frontendUser)
    {
        $this->model = $frontendUser;
    }

    /**
     *创建总代与用户后台管理员操作
     * @param  BackEndApiMainController $contll     后台api主控制器.
     * @param  array                    $inputDatas 请求数据.
     * @return JsonResponse
     */
    public function execute(BackEndApiMainController $contll, array $inputDatas): JsonResponse
    {
        $inputDatas['password'] = bcrypt($inputDatas['password']);
        $inputDatas['fund_password'] = bcrypt($inputDatas['fund_password']);
        $inputDatas['platform_id'] = $contll->currentPlatformEloq->platform_id;
        $inputDatas['sign'] = $contll->currentPlatformEloq->platform_sign;
        $inputDatas['vip_level'] = 0;
        $inputDatas['parent_id'] = 0;
        $inputDatas['register_ip'] = request()->ip();
        $inputDatas['pic_path'] = UserPublicAvatar::getRandomAvatar();
        DB::beginTransaction();
        try {
            $user = $this->model::create($inputDatas);
            $userId = $user->id;
            $user->rid = $userId;
            $userAccountEloq = new FrontendUsersAccount();
            $userAccountData = [
                'user_id' => $userId,
                'balance' => 0,
                'frozen' => 0,
                'status' => 1,
            ];
            $userAccountEloq = $userAccountEloq->fill($userAccountData);
            $userAccountEloq->save();
            $user->account_id = $userAccountEloq->id;
            $user->save();
            $userInfo = new FrontendUsersSpecificInfo();
            $userSpecificInfoData = ['user_id' => $userId];
            $userInfo::create($userSpecificInfoData);
            DB::commit();
            $data['name'] = $user->username;
            return $contll->msgOut(true, $data);
        } catch (Exception $exception) {
            DB::rollBack();
            return $contll->msgOut(false, [], $exception->getCode(), $exception->getMessage());
        }
//        $success['token'] = $user->createToken('前端')->accessToken;
    }
}
