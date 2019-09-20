<?php

namespace App\Http\SingleActions\Frontend;

use App\Http\Controllers\FrontendApi\FrontendApiMainController;
use App\Models\SystemPlatform;
use App\Models\User\FrontendLinksRegisteredUsers;
use App\Models\User\FrontendUser;
use App\Models\User\FrontendUsersRegisterableLink;
use App\Models\User\FrontendUsersSpecificInfo;
use App\Models\User\Fund\FrontendUsersAccount;
use App\Models\User\UserPublicAvatar;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class FrontendAuthRegisterAction
{
    protected $model;
    /**
     * FrontendAuthRegisterAction constructor.
     * @param FrontendUser $frontendUser
     */
    public function __construct(FrontendUser $frontendUser)
    {
        $this->model = $frontendUser;
    }

    /**
     * 用户注册
     * 0.普通注册
     * 1.人工开户注册
     * 2.链接开户注册
     * 3.扫码开户注册
     * @param  FrontendApiMainController $contll
     * @param  array $inputDatas
     * @return JsonResponse
     */
    public function execute(FrontendApiMainController $contll, array $inputDatas): JsonResponse
    {
        //注册类型
        $registerType = (int) $inputDatas['register_type'] ?? 0;
        $re_password = $inputDatas['re_password'] ?? '';

        if ($re_password !== '' && $re_password !== $inputDatas['password']) {
            return $contll->msgOut(false, [], '100008');
        }

        $inputDatas['vip_level'] = 0;
        $inputDatas['parent_id'] = 0;
        $inputDatas['top_id'] = 0;
        $userRid = '';
        $link = null;

        $resource = $this->diffeTypeOperate($contll, $inputDatas, $registerType, $userRid, $link);
        if ($resource['success'] === false) {
            return $contll->msgOut(false, [], $resource['code']);
        }

        $verifyData = $this->verifyData($inputDatas, $registerType);
        if ($verifyData['success'] === false) {
            return $contll->msgOut(false, [], $verifyData['code']);
        }

        $inputDatas['password'] = bcrypt($inputDatas['password']);
        $inputDatas['register_ip'] = request()->ip();
        $inputDatas['pic_path'] = UserPublicAvatar::getRandomAvatar();
        $inputDatas['sign'] = $inputDatas['platform_sign'];
        unset($inputDatas['keyword']);
        unset($inputDatas['platform_sign']);
        unset($inputDatas['host']);
        unset($inputDatas['register_type']);

        //插入信息
        DB::beginTransaction();
        try {
            $user = $this->model::create($inputDatas);
            $this->insertExtendedData($user, $registerType, $link, $userRid);

            $data['name'] = $user->username;
            //普通注册，用户登录
            if ($registerType === 0) {
                $this->userLogin($contll, $data);
            }
            return $contll->msgOut(true, $data);
        } catch (Exception $e) {
            DB::rollBack();
            return $contll->msgOut(false, [], $e->getCode(), $e->getMessage());
        }
    }

    private function updateTotalMembers(FrontendUser $newUser)
    {
        $resource = ['status' => true];
        if (!empty($newUser)) {
            if (empty($newUser->rid)) { //判断是否有Rid
                return $resource; //没有rid 无需修改
            } else {
                $userRid = str_replace('|', ',', trim($newUser->rid));
                $userRid = trim(str_replace($newUser->id, '', $userRid), ',');
                $userRid = explode(',', $userRid);
                DB::table('frontend_users_specific_infos')
                    ->whereIn('user_id', $userRid)
                    ->increment('total_members', 1);
            }
        }
        return $resource;
    }

    private function diffeTypeOperate($contll, &$inputDatas, $registerType, &$userRid, &$link)
    {
        if ($registerType === 0) {
            $resource = $this->ordinary($inputDatas); //0.普通注册
        } elseif ($registerType === 1) {
            $resource = $this->artificial($contll, $inputDatas, $userRid); //1.人工开户注册
        } elseif ($registerType === 2 || $registerType === 3) {
            $resource = $this->linkScanCode($inputDatas, $userRid, $link); //2.链接开户注册和扫码开户
        } else {
            $resource = $this->returnArr();
        }
        return $resource;
    }

    //普通注册
    private function ordinary(&$inputDatas)
    {
        $hostPlatform = configure('host_platform_settings');
        $hostPlatform = json_decode($hostPlatform, true);

        if (!isset($hostPlatform[$inputDatas['host']])) {
            return $this->returnArr(false, '100020');
        }

        $plat = $hostPlatform[$inputDatas['host']];
        isset($plat['platform_id']) && $inputDatas['platform_id'] = $plat['platform_id'];
        isset($plat['platform_sign']) && $inputDatas['platform_sign'] = $plat['platform_sign'];
        $inputDatas['type'] = 3; //用户类型你:1直属2代理3会员
        $inputDatas['prize_group'] = configure('min_bet_prize_group'); //普通注册默认平台最低奖金组
        return $this->returnArr(true);
    }

    //人工开户注册
    private function artificial($contll, &$inputDatas, &$userRid)
    {
        $inputDatas['prize_group'] = (int) $inputDatas['prize_group'] ?? 0;
        if ($inputDatas['prize_group'] === 0) {
            return $this->returnArr(false, '100015');
        }

        //当前用户需要登录
        $userInfo = $contll->currentAuth->user();
        if (!$userInfo) {
            return $this->returnArr(false, '100019');
        }

        $inputDatas['parent_id'] = $userInfo->id;
        $inputDatas['top_id'] = $userInfo->top_id;
        $inputDatas['platform_id'] = $contll->currentPlatformEloq->platform_id;
        $inputDatas['platform_sign'] = $contll->currentPlatformEloq->platform_sign;
        $userRid = $userInfo->rid;
        //平台最低奖金组
        $minPrizeGroup = configure('min_bet_prize_group');
        //平台最高奖金组
        $maxPrizeGroup = configure('max_bet_prize_group');

        if ($userInfo->prize_group < $maxPrizeGroup) {
            $maxPrizeGroup = $userInfo->prize_group;
        }

        if ($inputDatas['prize_group'] < $minPrizeGroup ||
            $inputDatas['prize_group'] > $maxPrizeGroup
        ) {
            return $this->returnArr(false, '100016');
        }
        return $this->returnArr(true);
    }

    //2.链接开户注册和扫码开户
    private function linkScanCode(&$inputDatas, &$userRid, &$link)
    {
        $keyword = $inputDatas['keyword'] ?? '';

        if ($keyword === '') {
            return $this->returnArr(false, '100017');
        }

        $link = FrontendUsersRegisterableLink::where('keyword', $keyword)->first();

        if (is_null($link)) {
            return $this->returnArr(false, '100018');
        }

        $inputDatas['prize_group'] = $link->prize_group;
        $inputDatas['parent_id'] = $link->user_id;
        $inputDatas['platform_id'] = $link->platform_id;
        $inputDatas['platform_sign'] = $link->platform_sign;
        $inputDatas['type'] = $link->user_type;

        $parent = FrontendUser::where('id', $link->user_id)->first();
        if (!$parent) {
            return $this->returnArr(false, '100021');
        }

        $userRid = $parent->rid;
        $inputDatas['top_id'] = $parent->top_id;
        return $this->returnArr(true);
    }

    private function returnArr($success = false, $code = '')
    {
        return ['success' => $success, 'code' => $code];
    }

    private function verifyData($inputDatas, $registerType)
    {
        if (!isset($inputDatas['platform_id']) || !isset($inputDatas['platform_sign'])) {
            return $this->returnArr(false, '100020');
        }

        //验证平台信息是否存在
        $platform = SystemPlatform::where('platform_id', $inputDatas['platform_id'])
            ->where('platform_sign', $inputDatas['platform_sign'])
            ->first();
        if (is_null($platform)) {
            return $this->returnArr(false, '100020');
        }

        if ($registerType > 0) {
            //每个用户最多拥有子账户数量，默认100个
            $usersChildNum = configure('users_child_num', 100);

            $childNum = $this->model->where('parent_id', $inputDatas['parent_id'])->count();

            if ($childNum >= $usersChildNum) {
                return $this->returnArr(false, '100022');
            }
        }
        return $this->returnArr(true);
    }

    //用户扩展信息
    private function insertExtendedData($user, $registerType, $link, $userRid)
    {
        $userRid !== '' && $userRid .= '|';
        $userRid .= $user->id;
        $user->rid = $userRid;

        //账户信息
        $userAccountEloq = new FrontendUsersAccount();
        $userAccountData = [
            'user_id' => $user->id,
            'balance' => 0,
            'frozen' => 0,
            'status' => 1,
        ];
        $userAccountEloq = $userAccountEloq->fill($userAccountData);
        $userAccountEloq->save();
        $user->account_id = $userAccountEloq->id;
        $user->save();

        //链接开户，扫码开户
        if ($registerType === 2 || $registerType === 3) {
            $registeredUser = new FrontendLinksRegisteredUsers();
            $registeredUser->register_link_id = $link->id;
            $registeredUser->user_id = $user->id;
            $registeredUser->url = $link->url;
            $registeredUser->username = $user->username;
            $registeredUser->save();
        }

        //附属信息
        $FrontendUsersSpecificInfo = new FrontendUsersSpecificInfo();
        $SpecificInfo = [
            'register_type' => $registerType,
            'user_id' => $user->id,
        ];
        $FrontendUsersSpecificInfo = $FrontendUsersSpecificInfo->fill($SpecificInfo);
        $FrontendUsersSpecificInfo->save();
        //维护该字段，防止其他报错
        $user->update([
            'user_specific_id' => $FrontendUsersSpecificInfo->id,
        ]);
        self::updateTotalMembers($user);
        DB::commit();
    }

    private function userLogin($contll, &$data)
    {
        $credentials = request(['username', 'password']);
        $token = $contll->currentAuth->attempt($credentials);
        $expireInMinute = $contll->currentAuth->factory()->getTTL();
        $expireAt = Carbon::now()->addMinutes($expireInMinute)->format('Y-m-d H:i:s');

        $data = [
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expires_at' => $expireAt,
        ];
    }
}
