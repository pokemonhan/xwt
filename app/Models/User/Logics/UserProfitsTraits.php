<?php

namespace App\Models\User\Logics;

use App\Models\User\FrontendUser;

trait UserProfitsTraits
{
    /**
     * @param FrontendUser $user 用户.
     * @param string       $date 日期.
     * @return mixed
     */
    public static function getUserProfits(FrontendUser $user, string $date)
    {
        return self::where([
            ['user_id', $user->id],
            ['date', $date],
        ])->first();
    }
}
