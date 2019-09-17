<?php

namespace App\Models\Partner;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class PartnerAdminGroup extends Model
{
    // 如果未设置 默认是蛇形复数形式的表明
    protected $table = 'partner_admin_groups';

    public $rules = [
        'name'              => 'required|min:2|max:32',
        'remark'            => 'required|min:2|max:128',
        'partner_sign'      => 'required|exists:partners,sign',
    ];

    /**
     * 后去用户可以配置的下级
     * @param string $c
     * @param int $pageSize
     * @return array
     */
    static function getAdminGroupList($c, $pageSize = 20) {
        $query = self::orderBy('id', 'DESC');

        $currentPage    = isset($c['pageIndex']) ? intval($c['pageIndex']) : 1;
        $pageSize       = isset($c['pageSize']) ? intval($c['pageSize']) : $pageSize;
        $offset         = ($currentPage - 1) * $pageSize;

        $total  = $query->count();
        $items  = $query->skip($offset)->take($pageSize)->get();

        return ['data' => $items, 'total' => $total, 'currentPage' => $currentPage, 'totalPage' => intval(ceil($total / $pageSize))];
    }

    /**
     * 创建商户的时候　初始化　管理组
     * @param $partnerSign
     * @param $adminUser
     * @return PartnerAdminGroup
     */
    static function initSuperGroup($partnerSign, $adminUser = null) {
        $query = new self();
        $query->name             = "超级管理";
        $query->partner_sign     = $partnerSign;
        $query->remark           = "预设－超级管理";
        $query->acl              = '*';
        $query->is_super_group   = 1;
        $query->add_admin_id     = $adminUser ? $adminUser->id : 99999;
        $query->status           = 1;
        $query->save();

        return $query;
    }

    /**
     * 添加一个管理员到制定的组上
     * @param $email
     * @param $password
     * @param $fundPassword
     * @param null $adminUser
     * @return bool
     */
    public function addSuperAdminUser($email, $password, $fundPassword, $adminUser = null) {
        $data = [
            'email'         => $email,
            'username'      => strtolower($this->partner_sign) . "_super",
            'password'      => $password,
            'fund_password' => $fundPassword,
            'partner_sign'  => $this->partner_sign,
        ];

        $model = new PartnerAdminUser();
        $partnerAdminUser = $model->saveItem($data, $adminUser);
        if (!is_object($partnerAdminUser)) {
            return $partnerAdminUser;
        }

        // 绑定用户到组
        return PartnerAdminGroupUser::bindUserToGroup($this, $partnerAdminUser, $adminUser);
    }

    // 获取组包含的权限
    public function getAcl() {
        $acl = $this->acl;

        if (!$acl) {
            return [];
        }

        if ($acl == '*') {
            $menus = PartnerMenu::where('partner_sign', $this->partner_sign)->pluck("menu_id");
            $menuIds = $menus->toArray();
        } else {
            $menuIds = unserialize($acl);
        }

        $allMenus = PartnerMenu::getPartnerBindMenu($this->partner_sign, $menuIds);

        return $allMenus;
    }

    /**
     * 设置权限
     * @param $ids
     */
    public function setAcl($ids) {
        $data = [];
        foreach ($ids as $menuId) {
            $data[] = [
                'partner_sign'  => $this->partner_sign,
                'menu_id'       => $menuId,
                'status'        => 1,
                'created_at'    => now()
            ];
        }

        self::insert($data);
    }


    /**
     * 添加管理组
     * @param $data
     * @param null $adminUser
     * @return bool|string
     */
    public function saveItem($data, $adminUser = null) {
        $validator  = Validator::make($data, $this->rules);

        if ($validator->fails()) {
            return $validator->errors()->first();
        }

        // 邮箱 不能重复
        if (!$this->id) {
            $count = self::where('name', '=', $data['name'])->where("partner_sign", $data['partner_sign'])->count();
            if ($count > 0) {
                return "对不起, 组名(name)已经存在!!";
            }
        } else {
            $count = self::where('name', '=', $data['name'])->where("partner_sign", $data['partner_sign'])->where("id", "<>", $this->id)->count();
            if ($count > 0) {
                return "对不起, 组名(name)已经存在!!";
            }
        }

        $this->name             = $data['name'];
        $this->partner_sign     = $data['partner_sign'];
        $this->remark           = $data['remark'];
        $this->acl              = '';
        $this->add_admin_id     = $adminUser ? $adminUser->id : 99999;
        $this->save();

        return true;
    }

    /**
     * 检查是不是某一个平台的下级
     * @param $pid
     * @return bool
     */
    public function isChildGroup($pid) {
        $parentArr = explode('|', $this->rid);
        if(in_array($pid, $parentArr)) {
            return true;
        }
        return false;
    }

    /**
     * 获取管理组选项
     * @param int $pid
     * @return array
     */
    static function getGroupOptions($pid = 0) {
        $groupList = PartnerAdminGroup::where("pid", $pid)->get();
        $options = [];
        foreach ($groupList as $group) {
            $options[$group->id] = $group->name;
        }

        return $options;
    }
}
