<?php

namespace App\Models\User\Fund\Logics;

use App\Lib\BaseCache;
use Illuminate\Support\Facades\DB;

/**
 * Created by PhpStorm.
 * author: Harris
 * Date: 5/31/2019
 * Time: 7:59 PM
 */
trait FrontendUsersAccountsTypeLogics
{
    use BaseCache;

    public static function getList($c)
    {
        $query = self::orderBy('id', 'desc');

        $currentPage = isset($c['page_index']) ? (int) $c['page_index'] : 1;
        $pageSize = isset($c['page_size']) ? (int) $c['page_size'] : 15;
        $offset = ($currentPage - 1) * $pageSize;

        $total = $query->count();
        $data = $query->skip($offset)->take($pageSize)->get();

        return [
            'data' => $data,
            'total' => $total,
            'currentPage' => $currentPage,
            'totalPage' => (int) ceil($total / $pageSize),
        ];
    }

    // 保存
    public function saveItem($data, $adminId = 0)
    {
        $this->name = $data['name'];
        $this->sign = $data['sign'];
        $this->in_out = $data['in_out'];

        $this->amount = $data['amount'] > 0 ? 1 : 0;
        $this->user_id = $data['user_id'] > 0 ? 1 : 0;
        $this->project_id = $data['project_id'] > 0 ? 1 : 0;
        $this->lottery_id = $data['lottery_id'] > 0 ? 1 : 0;
        $this->method_id = $data['method_id'] > 0 ? 1 : 0;
        $this->issue = $data['issue'] > 0 ? 1 : 0;
        $this->from_id = $data['from_id'] > 0 ? 1 : 0;
        $this->from_admin_id = $data['from_admin_id'] > 0 ? 1 : 0;
        $this->to_id = $data['to_id'] > 0 ? 1 : 0;
        $this->frozen_type = $data['frozen_type'] > 0 ? 1 : 0;
        $this->activity_sign = $data['activity_sign'] > 0 ? 1 : 0;
        $this->admin_id = $adminId;
        $this->save();
        return true;
    }

    /**
     * 获取具体详情
     * @param string $sign
     * @return array|mixed
     */
    public static function getTypeBySign($sign)
    {
        $data = self::getDataListFromCache();
        return $data[$sign] ?? [];
    }

    // 获取所有配置 缓存
    public static function getDataListFromCache()
    {
        $key = 'account_change_type';
        if (self::hasTagsCache($key)) {
            return self::getTagsCacheData($key);
        } else {
            $allCache = self::getDataFromDb();
            if ($allCache) {
                self::saveTagsCacheData($key, $allCache);
            }
            return $allCache;
        }
    }

    // 获取所有数据 无缓存
    public static function getDataFromDb()
    {
        $items = self::orderBy('id', 'desc')->get();
        $data = [];
        foreach ($items as $item) {
            $data[$item->sign] = $item->toArray();
        }
        return $data;
    }

    /**
     * @param  string  $sType
     * @return array
     */
    public static function getParamToTransmit($sType = ''): array
    {
        $accTypeParams = DB::table('frontend_users_accounts_types as fuat')
            ->leftJoin('frontend_users_accounts_types_params as fuatp', static function ($join) {
                $join->whereRaw('find_in_set(fuatp.id, fuat.param)');
            })->select('fuat.*', DB::raw('GROUP_CONCAT(fuatp.param) as param'))
            ->where('sign', $sType)
            ->groupBy(DB::raw('fuat.id'))->pluck('param');
        $params = explode(',', $accTypeParams[0]);
        $paramsFlipped = array_flip($params);
        return array_fill_keys(array_keys($paramsFlipped), 'required');
    }

    /**
     * @param  array  $field
     * @return mixed
     */
    public static function getTypeList($field)
    {
        return self::all($field);
    }
}
