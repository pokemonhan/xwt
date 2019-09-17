<?php

namespace App\Models\Partner;

use Illuminate\Database\Eloquent\Model;

class PartnerAdminBehavior extends Model
{
    protected $table = 'partner_admin_behavior';

    /**
     * 获取行为列表
     * @param $c
     * @param $pageSize
     * @return mixed
     */
    static function getList($c, $pageSize = 15) {
        $query = self::orderBy('id', 'DESC');

        // 平台
        if (isset($c['partner_sign']) && $c['partner_sign'] && $c['partner_sign'] != "all") {
            $query->where('partner_sign', $c['partner_sign']);
        }

        // 用户名
        if (isset($c['admin_id']) && $c['admin_id']) {
            $query->where('admin_id', $c['admin_id']);
        }

        // 用户名
        if (isset($c['username']) && $c['username']) {
            $query->where('username', $c['username']);
        }

        // 分类
        if (isset($c['type_id']) && $c['type_id'] && $c['type_id'] != "all") {
            $query->where('type_id', $c['type_id']);
        }

        $currentPage    = isset($c['pageIndex']) ? intval($c['pageIndex']) : 1;
        $pageSize       = isset($c['pageSize']) ? intval($c['pageSize']) : $pageSize;
        $offset         = ($currentPage - 1) * $pageSize;

        $total  = $query->count();
        $items  = $query->skip($offset)->take($pageSize)->get();

        return ['data' => $items, 'total' => $total, 'currentPage' => $currentPage, 'totalPage' => intval(ceil($total / $pageSize))];
    }
}
