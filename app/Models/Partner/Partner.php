<?php

namespace App\Models\Partner;

use App\Lib\Clog;
use App\Models\GameCasino\CasinoPlatform;
use App\Models\Player\Player;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class Partner extends Model
{
    protected $table = 'partners';

    public $rules = [
        'name'                  => 'required|min:2|max:64',
        'sign'                  => 'required|min:2|max:32',
        'top_player_username'   => 'required|min:2|max:64',
        'password'              => 'required|min:2|max:64',
        'admin_email'           => 'required|min:2|max:64',
        'admin_password'        => 'required|min:2|max:64',
        'admin_fund_password'   => 'required|min:2|max:64',
    ];

    /**
     * 获取列表
     * @param $c
     * @return mixed
     */
    static function getList($c) {
        $query = self::orderBy('id', 'DESC');

        // 用户名
        if (isset($c['sign']) && $c['sign'] && $c['sign'] != "all") {
            $query->where('sign', $c['sign']);
        }

        $currentPage    = isset($c['pageIndex']) ? intval($c['pageIndex']) : 1;
        $pageSize       = isset($c['pageSize']) ? intval($c['pageSize']) : 15;
        $offset         = ($currentPage - 1) * $pageSize;

        $total  = $query->count();
        $items  = $query->skip($offset)->take($pageSize)->get();
        $ids = [];
        foreach ($items as $item) {
            $ids[] = $item->sign;
        }

        // 获取所有匹配的 casino platform
        $allPlatform    = PartnerCasinoPlatform::getAllPlatformBySign($ids);
        $allPlayers     = PartnerTopPlayer::getAllPlayerBySign($ids);
        $allMenus       = PartnerMenu::getAllMenuBySign($ids);

        foreach ($items as $item) {
            $item->casino_platform  = isset($allPlatform[$item->sign]) ? $allPlatform[$item->sign] : [];
            $item->top_players      = isset($allPlayers[$item->sign]) ? $allPlayers[$item->sign] : [];
            $item->menus            = isset($allMenus[$item->sign]) ? $allMenus[$item->sign] : [];
        }

        return ['data' => $items, 'total' => $total, 'currentPage' => $currentPage, 'totalPage' => intval(ceil($total / $pageSize))];
    }

    // 保存
    public function saveItem($data, $admin = null) {
        db()->beginTransaction();
        try {
            $validator  = Validator::make($data, $this->rules);
            if ($validator->fails()) {
                return $validator->errors()->first();
            }

            // Sign 不能重复
            if (!$this->id) {
                $count = self::where('sign', '=', $data['sign'])->count();
                if ($count > 0) {
                    return "对不起, 标识(sign)已经存在!!";
                }
            } else {
                $count = self::where('sign', '=', $data['sign'])->where("id", "<>", $this->id)->count();
                if ($count > 0) {
                    return "对不起, 标识(sign)已经存在!!";
                }
            }

            // username 不能重复
            if (!$this->id) {
                $count = self::where('top_player_username', '=', $data['top_player_username'])->count();
                if ($count > 0) {
                    return "对不起, 直属用户名(username)已经存在!!";
                }
            }

            // 分配所有菜单
            PartnerMenu::initPartnerMenu($data['sign']);

            // 添加直属
            $top = Player::addTop($data['top_player_username'], $data['password'], 0, $data['top_player_username']);
            if (!is_object($top)) {
                return $top;
            }

            // admin_email 不能重复
            if (!$this->id) {
                $count = PartnerAdminUser::where('email', '=', $data['admin_email'])->where('partner_sign', $data['sign'])->count();
                if ($count > 0) {
                    return "对不起, 超级管理员邮箱(admin_email)已经存在!!";
                }
            }

            // 添加超级管理组
            $group = PartnerAdminGroup::initSuperGroup($data['sign'], $admin);
            if (!$group) {
                db()->rollback();
                return "对不起, 添加管理组失败!";
            }

            $this->name                     = $data['name'];
            $this->sign                     = $data['sign'];

            $this->top_player_id            = $top->id;
            $this->top_player_username      = $top->username;

            $this->theme                    = isset($data['theme']) ? $data['theme'] : 'default';
            $this->remark                   = $data['remark'];
            $this->add_admin_id             = $admin ? $admin->id : '999999';
            $this->save();

            $this->api_key                  = md5($data['sign'] . "_" . $this->id);
            $this->save();
            // 添加超级管理员
            $res = $group->addSuperAdminUser($data['admin_email'], $data['admin_password'], $data['admin_fund_password'], $admin);
            if (!is_object($res)) {
                db()->rollback();
                return $res;
            }

            db()->commit();
        } catch (\Exception $e) {
            db()->rollback();
            Clog::partner("partner-add-partner-:" . $e->getMessage() . "|" . $e->getLine() . "|" . $e->getFile());
            return $e->getMessage();
        }

        return true;
    }

    // 设置娱乐城平台
    public function setCasinoPlatform($codeArr, $adminUser = null) {
        // 检测code是否合法
        $total = CasinoPlatform::whereIn('main_game_plat_code', $codeArr)->where('status', 1)->count();
        if (count($codeArr) != $total) {
            return "对不起, 包含无效的平台Code!s";
        }

        // 上出所有的老的
        PartnerCasinoPlatform::where("partner_sign", $this->sign)->delete();

        // 插入
        $data = [];
        foreach ($codeArr as $code) {
            $data[] = [
                'partner_sign'  => $this->sign,
                'platform_code' => $code,
                'status'        => 1,
                'add_admin_id'  => $adminUser ? $adminUser->id : 999999
            ];
        }

        PartnerCasinoPlatform::insert($data);
        return true;
    }

    // 设置娱乐城平台
    public function setTopPlayer($idArr, $adminUser = null) {
        // 检测用户ID是否存在
        $total = Player::whereIn('id', $idArr)->where('status', 1)->count();
        if (count($idArr) != $total) {
            return "对不起, 包含无效的用户";
        }

        // 总代
        PartnerTopPlayer::where("partner_sign", $this->sign)->delete();

        // 插入
        $data = [];
        foreach ($idArr as $userId) {
            $data[] = [
                'partner_sign'  => $this->sign,
                'top_id'        => $userId,
                'status'        => 1,
                'add_admin_id'  => $adminUser ? $adminUser->id : 999999
            ];
        }

        PartnerTopPlayer::insert($data);
        return true;
    }

    // 设置 菜单
    public function setAdminMenus($idArr, $adminUser = null) {
        // 检测用户ID是否存在
        $menus = PartnerMenuConfig::whereIn('id', $idArr)->where('status', 1)->get();
        if (count($idArr) != count($menus)) {
            return "对不起, 包含无效的用户";
        }

        // 把上级加上去
        foreach ($menus as $menu) {
            if ($menu->pid > 0 && !in_array($menu->pid, $idArr)) {
                $idArr[] = $menu->pid;
            }
        }

        // 总代
        PartnerMenu::where("partner_sign", $this->sign)->delete();

        // 插入
        $data = [];
        foreach ($idArr as $menuId) {
            $data[] = [
                'partner_sign'  => $this->sign,
                'menu_id'       => $menuId,
                'status'        => 1,
                'add_admin_id'  => $adminUser ? $adminUser->id : 999999
            ];
        }

        PartnerMenu::insert($data);
        return true;
    }

    /**
     * 获取选项
     * @return array
     */
    static function getOptions() {
        $items  = self::where('status', 1)->get();
        $data   = [];
        foreach ($items as $item) {
            $data[$item->sign] = $item->name;
        }

        return $data;
    }
}
