<?php
namespace App\Models\Partner;

use App\Models\BaseCache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PartnerMenu extends Model
{

    use BaseCache;

    // 如果未设置 默认是蛇形复数形式的表明
    protected $table = 'partner_menus';

    public $rules = [
        "title"         => "required|min:2,max:32",
        "route"         => "required|min:0,max:32",
        "type"          => "required|in:0,1",
    ];

    /**
     * @param $condition
     * @param $pageSize
     * @return array
     */
    static function getMenuList($condition, $pageSize = 20) {
        $query = self::select(
            DB::raw('partner_menus.*'),
            DB::raw('partner_menu_config.route as menu_route'),
            DB::raw('partner_menu_config.title as menu_title'),
            DB::raw('partner_menu_config.type as menu_type')
        )->leftJoin('partner_menu_config', 'partner_menu_config.id', '=', 'partner_menus.menu_id')->orderBy('partner_menus.id', 'desc');

        // 菜单类型
        if (isset($condition['menu_type']) && in_array($condition['menu_type'], ['0', '1'])) {
            $query->where('partner_menu_config.type', $condition['menu_type']);
        }

        // 平台
        if (isset($condition['partner_sign']) && $condition['partner_sign'] != 'all') {
            $query->where('partner_menus.partner_sign', $condition['partner_sign']);
        }

        $currentPage    = isset($condition['pageIndex']) ? intval($condition['pageIndex']) : 1;
        $pageSize       = isset($condition['pageSize']) ? intval($condition['pageSize']) : $pageSize;
        $offset         = ($currentPage - 1) * $pageSize;

        $total  = $query->count();
        $menus  = $query->skip($offset)->take($pageSize)->get();

        $typeOptions = PartnerMenuConfig::$typeOptions;
        foreach ($menus as $_menu) {
            $_menu->menu_type = isset($typeOptions[$_menu->menu_type]) ? $typeOptions[$_menu->menu_type] : $_menu->menu_type;
        }

        return ['data' => $menus, 'total' => $total, 'currentPage' => $currentPage, 'totalPage' => intval(ceil($total / $pageSize))];
    }

    /**
     * 获取商户对应开放的菜单
     * @param $signArr
     * @return array
     */
    static function getAllMenuBySign($signArr) {
        $query = self::select(
            DB::raw('partner_menus.menu_id'),
            DB::raw('partner_menus.partner_sign'),
            DB::raw('partner_menu_config.title as title'),
            DB::raw('partner_menu_config.route as route')
        )->leftJoin('partner_menu_config', 'partner_menu_config.id', '=', 'partner_menus.menu_id')->orderBy('partner_menus.id', 'desc');

        $items  = $query->whereIn('partner_menus.partner_sign', $signArr)->get();

        $data = [];
        foreach ($items as $item) {
            if (!isset($data[$item->partner_sign])) {
                $data[$item->partner_sign] = [];
            }

            $data[$item->partner_sign][] = [
                'menu_id'   => $item->menu_id,
                'title'     => $item->title,
            ];
        }

        return $data;
    }

    /**
     * 获取菜单层级关系
     * @param int $pid
     * @return mixed
     */
    static function getMenuRelated($pid) {
        $menu = self::find($pid);
        if (!$menu || !$menu->rid) {
            return [];
        }

        $ids    = explode('|', $menu->rid);
        $menus  = self::whereIn('id', $ids)->get();
        return $menus;
    }

    /**
     * 初始化平台的菜单
     * @param $sign
     */
    static function initPartnerMenu($sign) {
        $allMenus = PartnerMenuConfig::where('status', 1)->get();

        $data = [];
        foreach ($allMenus as $menu) {
            $data[] = [
                'partner_sign'  => $sign,
                'menu_id'       => $menu->id,
                'status'        => 1,
                'created_at'    => now()
            ];
        }

        self::insert($data);
    }

    /**
     * 获取商户绑定的菜单
     * @param $partnerSign
     * @param array $checkedMenu  已经绑定的
     * @return array
     */
    static function getPartnerBindMenu($partnerSign, $checkedMenu = []) {
        $query = self::select(
            DB::raw('partner_menus.partner_sign'),
            DB::raw('partner_menu_config.*')
        )->leftJoin('partner_menu_config', 'partner_menu_config.id', '=', 'partner_menus.menu_id')
        ->where("partner_menus.partner_sign", $partnerSign)->orderBy('partner_menus.id', 'desc');

        $menus = $query->get();
        $parentSet = [];
        foreach ($menus as $menu) {
            if ($menu->pid > 0) {
                if (!isset($parentSet[$menu->pid]) ) {
                    $parentSet[$menu->pid] = [];
                }
                $parentSet[$menu->pid][] = $menu;
            }

            // 是否会被选中
            if (in_array($menu->id, $checkedMenu)) {
                $menu['checked'] = true;
            } else {
                $menu['checked'] = false;
            }
        }

        // 设置层级
        $data = [];
        foreach ($menus as &$menu) {

            if (!$menu->pid) {

                if (isset($parentSet[$menu->id])) {
                    $menu->child = $parentSet[$menu->id];
                    foreach ($menu->child as $_menu) {
                        if (isset($parentSet[$_menu->id])) {
                            $_menu->child = $parentSet[$_menu->id];
                        }
                    }
                }

                $data[] = $menu;
            }
        }

        return $data;
    }

    /**
     * All Route
     * @return array
     */
    static function getApiAllRoute() {
        $adminUser  = auth()->guard('admin_api')->user();
        $groups     = $adminUser->groups();
        $allAcl     = PartnerMenu::getAclIds($groups);

        $menus      = self::where('status', '=', 1)->orderBy('sort', 'ASC')->get();

        $secondLevelMenu = [];
        $data   = [];
        foreach ($menus as $m) {
            if (!$m->pid &&  $m->type != 1 && in_array($m->id, $allAcl)) {
                $data[$m->id] = [
                    'is_menu'       => $m->type ? false : true,
                    'id'            => $m->id,
                    'pid'           => $m->pid,
                    'title'         => $m->title,
                    'css_class'     => $m->css_class,
                    'api_path'      => "",
                    'api_route'     => "",
                    'childs'        => [],
                ];
            }

            if ($m->type == 1 && in_array($m->id, $allAcl)) {
                if (!isset($secondLevelMenu[$m->pid])) {
                    $secondLevelMenu[$m->pid] = [];
                }

                $secondLevelMenu[$m->pid][] = [
                    'is_menu'       => $m->type ? false : true,
                    'id'            => $m->id,
                    'pid'           => $m->pid,
                    'title'         => $m->title,
                    'css_class'     => $m->css_class,
                    'api_path'      => $m->api_path,
                    'api_route'     => "api/" . $m->route,
                    'childs'        => [],
                ];
            }
        }

        //  二级菜单
        foreach ($menus as $m) {
            if ($m->pid && $m->type != 1 && in_array($m->id, $allAcl)) {
                $data[$m->pid]['childs'][] = [
                    'is_menu'       => $m->type ? false : true,
                    'id'            => $m->id,
                    'pid'           => $m->pid,
                    'title'         => $m->title,
                    'css_class'     => $m->css_class,
                    'api_path'      => $m->api_path,
                    'api_route'     => "api/" . $m->route,
                    'childs'        => isset($secondLevelMenu[$m->id]) ??[],
                ];
            }
        }

        return array_values($data);
    }

    /**
     * 获取可用权限menu Id
     * @param $group
     * @param $hasRoute
     * @return array|mixed
     */
    static function getAclIds($group) {
        if ($group->acl == "*") {
            $menus  = self::where('status',  '=', 1)->orderBy('id',   'ASC')->get();
            $allIds    = [];
            foreach ($menus as $m) {
                $allIds[] = $m->id;
            }
        } else {
            $acl    =  $group->acl ? unserialize($group->acl) : [];
            $menus  = self::where('status',  '=', 1)->whereIn('id', $acl)->orderBy('id',   'ASC')->get();

            $allIds = [];
            foreach ($menus as $m) {
                $allIds[] = $m->id;
                if ($m->rid) {
                    $ids = explode("|", $m->rid);
                    foreach ($ids as $id) {
                        if (!in_array($id, $allIds)) {
                            $allIds[] = $id;
                        }
                    }
                }
            }
        }
        return $allIds;
    }

    /**
     * 获取菜单路由
     * @param $ids
     * @return array
     */
    static function getAllMenuRoute($ids) {
        $menus  = self::whereIn('id', $ids)->get();
        $data   = [];
        foreach ($menus as $menu) {
            $data[] = $menu->route;
        }
        return $data;
    }

    /**
     * 获取权限
     * @param array $allIds
     * @return array
     */
    static function getAclMenus($allIds = []) {

        // 带上级的
        $allMenus   = self::where('status',  '=', 1)->whereIn('id', $allIds)->orderBy('sort',   'ASC')->get();

        $aclMenus   = [];

        $parentMenus = [];
        foreach ($allMenus as $menu) {
            if (!$menu->pid) {
                if(in_array($menu->id, $allIds)) {
                    $aclMenus[$menu->id] = [
                        'title' => $menu->title,
                        'route' => $menu->route,
                        'child' => []
                    ];
                }
            }

            $aRid = explode('|', $menu->rid);

            if (count($aRid) == 3) {
                if (!isset($parentMenus[$menu->pid])) {
                    $parentMenus[$menu->pid] = [];
                }
                $parentMenus[$menu->pid][$menu->id] = [
                    'title' => $menu->title,
                    'route' => $menu->route,
                    'child' => []
                ];
            }
        }

        foreach ($allMenus as $_menu) {
            $aRid = explode('|', $_menu->rid);
            if (count($aRid) == 2) {
                $aclMenus[$_menu->pid]['child'][$_menu->id] = [
                    'title' => $_menu->title,
                    'route' => $_menu->route,
                    'child' => isset($parentMenus[$_menu->id]) ? $parentMenus[$_menu->id] : []
                ];
            }

        }


        return $aclMenus;
    }
}
