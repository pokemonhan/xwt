<?php

namespace App\Models\User\Logics;

use App\Models\Admin\Notice\FrontendMessageNotice;

trait FrontendUserTraits
{
    /**
     * 获取所有用户id
     * @return Collection|static[]
     */
    public static function getAllUserIds()
    {
        return self::select('id')->get();
    }

    /**
     * 用户未读站内信数量
     * @return integer
     */
    public function unreadMessageNum()
    {
        $message = $this->message;
        return $message->where('status', FrontendMessageNotice::STATUS_UNREAD)->count();
    }

    /**
     * 根据username获取用户
     * @param string $username 用户名.
     * @return mixed
     */
    public static function nameGetUser(string $username)
    {
        return self::where('username', $username)->first();
    }

    /**
     * 获取所有下级（包括自己）的id
     * @param integer $userId 用户id.
     * @return array
     */
    public static function getSubIds(int $userId)
    {
        $users = self::where('id', $userId)->orWhere('parent_id', $userId)->pluck('id');
        $userIds = [];
        if ($users->isNotEmpty()) {
            $userIds = $users->toArray();
        }
        return $userIds;
    }

    /**
     * 获取自己和下级的总余额
     * @return float
     */
    public function getTeamBalance()
    {
        $selfBalance = $this->account->balance;
        $childrenBalance = $this->with('children.account:balance')->get()->sum('balance');
        $teamBalance = (float) ($selfBalance + $childrenBalance);
        return $teamBalance;
    }
}
