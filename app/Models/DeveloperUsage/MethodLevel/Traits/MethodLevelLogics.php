<?php

namespace App\Models\DeveloperUsage\MethodLevel\Traits;

use App\Lib\BaseCache;

trait MethodLevelLogics
{
    use BaseCache;
    /**
     * 后台玩法等级列表缓存
     * @param  integer $update
     * @return array
     */
    public static function methodLevelDetail($update = 0): array
    {
        $redisKey = 'lottery_method_leve_detail';
        $data = [];
        if ($update === 0) {
            $data = self::getTagsCacheData($redisKey);
        }
        if (empty($data)) {
            $methodtype = self::groupBy('method_id')->orderBy('id', 'asc')->get();
            foreach ($methodtype as $method) {
                $data[$method->method_id] = self::select('id', 'method_id', 'series_id', 'level', 'position', 'count', 'prize')
                    ->where('method_id', $method->method_id)
                    ->get()
                    ->toArray();
            }
            self::saveTagsCacheData($redisKey, $data);
        }
        return $data;
    }
}
