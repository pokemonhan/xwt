<?php
namespace App\Models\Partner;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * 平台绑定的直属
 * Class PartnerTopPlayer
 * @package App\Models\Partner
 */
class PartnerTopPlayer extends Model
{
    protected $table = 'partner_top_player';

    /**
     * 获取列表
     * @param $c
     * @return mixed
     */
    static function getList($c) {
        $query = self::orderBy('id', 'DESC');

        // 标识
        if (isset($c['partner_sign']) && $c['partner_sign'] && $c['partner_sign'] != "all") {
            $query->where('partner_sign', $c['partner_sign']);
        }

        // 直属
        if (isset($c['top_id']) && $c['top_id']) {
            $query->where('top_id', $c['top_id']);
        }

        $currentPage    = isset($c['pageIndex']) ? intval($c['pageIndex']) : 1;
        $pageSize       = isset($c['pageSize']) ? intval($c['pageSize']) : 15;
        $offset         = ($currentPage - 1) * $pageSize;

        $total  = $query->count();
        $items  = $query->skip($offset)->take($pageSize)->get();

        return ['data' => $items, 'total' => $total, 'currentPage' => $currentPage, 'totalPage' => intval(ceil($total / $pageSize))];
    }

    /**
     * 获取商户对应开放的平台
     * @param $signArr
     * @return array
     */
    static function getAllPlayerBySign($signArr) {
        $query = self::select(
            DB::raw('partner_top_player.top_id'),
            DB::raw('partner_top_player.partner_sign'),
            DB::raw('users.username as username')
        )->leftJoin('users', 'users.id', '=', 'partner_top_player.top_id')->orderBy('partner_top_player.id', 'desc');

        $items  = $query->whereIn('partner_top_player.partner_sign', $signArr)->get();

        $data = [];
        foreach ($items as $item) {
            if (!isset($data[$item->partner_sign])) {
                $data[$item->partner_sign] = [];
            }

            $data[$item->partner_sign][] = [
                'username'  => $item->username,
                'user_id'   => $item->top_id,
            ];
        }

        return $data;
    }
}
