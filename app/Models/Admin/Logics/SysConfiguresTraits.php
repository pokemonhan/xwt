<?php

namespace App\Models\Admin\Logics;

use App\Lib\BaseCache;

trait SysConfiguresTraits
{
    use BaseCache;

    /**
     * @param  string  $key
     * @return string
     */
    public static function getConfigValue($key = null):  ? string
    {
        if (empty($key)) {
            return $key;
        } else {
            return self::where('sign', $key)->value('value');
        }
    }

    /**
     * 获取网站基本配置
     * @param  integer $update [不等于0时  更新缓存]
     * @return array
     */
    public static function getWebInfo($update = 0) : array
    {
        $redisKey = 'frontend_web_info';
        $data = self::getTagsCacheData($redisKey);
        if (empty($data)) {
            $sysConfigEloq = self::where('sign', 'web_info')->first();
            if ($sysConfigEloq !== null) {
                $webConfigELoq = $sysConfigEloq->childs;
                foreach ($webConfigELoq as $webConfigItem) {
                    $data[$webConfigItem->sign] = $webConfigItem->value;
                }
            }
            self::saveTagsCacheData($redisKey, $data);
        }
        return $data;
    }
}
