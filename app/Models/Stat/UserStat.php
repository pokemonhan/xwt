<?php
namespace App\Models\Stat;

use App\Models\BaseModel;

/**
 * Class UserStat
 * @package App\Models\Stat
 */
class UserStat extends BaseModel
{
    protected $table = 'user_stat';

    /**
     * 获取列表
     * @param $topId
     * @param $c
     * @param int $pageSize
     * @return array
     */
    public static function getList($topId, $c, $pageSize = 15)
    {
        $query = self::where('top_id', $topId)->orderBy('id', 'desc');

        // 用户名
        if (isset($c['username']) && $c['username']) {
            $query->where('username', trim($c['username']));
        }

        // 用户名
        if (isset($c['nickname']) && $c['nickname']) {
            $query->where('nickname', trim($c['nickname']));
        }

        // 上级ID
        if (isset($c['parent_id']) && $c['parent_id']) {
            $query->where('parent_id', $c['parent_id']);
        }

        // 用户ID
        if (isset($c['user_id']) && $c['user_id']) {
            $query->where('user_id', $c['user_id']);
        }

        $currentPage    = isset($c['pageIndex']) ? intval($c['pageIndex']) : 1;
        $pageSize       = isset($c['pageSize']) ? intval($c['pageSize']) : $pageSize;
        $offset = ($currentPage - 1) * $pageSize;

        $total = $query->count();
        $data  = $query->skip($offset)->take($pageSize)->get();

        return [
            'data' => $data,
            'total' => $total,
            'currentPage' => $currentPage,
            'totalPage' => intval(ceil($total / $pageSize))
        ];
    }
}
