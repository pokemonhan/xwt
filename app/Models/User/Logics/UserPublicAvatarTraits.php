<?php

namespace App\Models\User\Logics;

trait UserPublicAvatarTraits
{
    /**
     * 获取所有用户id
     */
    public static function getRandomAvatar()
    {
        $data =  self::select('*')->get()->toArray();
        return  $data[array_rand($data, 1)]['pic_path'];
    }
    /**
     * 根据ID单条数据
     */
    public static function getAvatar($id)
    {
        return self::where('id', $id)->first();
    }
}
