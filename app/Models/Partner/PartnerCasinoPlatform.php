<?php

namespace App\Models\Partner;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PartnerCasinoPlatform extends Model
{
    protected $table = 'partner_casino_platform';

    public $rules = [
        'partner_sign'      => 'required|min:2|max:64',
        'platform_code'     => 'required|min:2|max:32',
    ];

    /**
     * 获取列表
     * @param $c
     * @return mixed
     */
    static function getList($c) {
        $query = self::orderBy('id', 'DESC');

        // 用户名
        if (isset($c['type']) && $c['type'] && $c['type'] != "all") {
            $query->where('type', $c['type']);
        }

        $currentPage    = isset($c['page_index']) ? intval($c['page_index']) : 1;
        $pageSize       = isset($c['page_size']) ? intval($c['page_size']) : 15;
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
    static function getAllPlatformBySign($signArr) {
        $query = self::select(
            DB::raw('partner_casino_platform.platform_code'),
            DB::raw('partner_casino_platform.partner_sign'),
            DB::raw('game_casino_platforms.main_game_plat_name as platform_name')
        )->leftJoin('game_casino_platforms', 'game_casino_platforms.main_game_plat_code', '=', 'partner_casino_platform.platform_code')->orderBy('partner_casino_platform.id', 'desc');

        $items  = $query->whereIn('partner_sign', $signArr)->get();

        $data = [];
        foreach ($items as $item) {
            if (!isset($data[$item->partner_sign])) {
                $data[$item->partner_sign] = [];
            }

            $data[$item->partner_sign][] = [
                'name' => $item->platform_name,
                'code' => $item->platform_code,
            ];
        }

        return $data;
    }
}
