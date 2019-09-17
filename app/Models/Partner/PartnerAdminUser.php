<?php

namespace App\Models\Partner;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Contracts\JWTSubject;

/**
 * Tom 2019
 * Class AdminUser
 * @package App\Models\Admin
 */
class PartnerAdminUser extends Authenticatable implements JWTSubject
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    public $rules = [
        'username'          => 'required|min:4|max:32',
        'email'             => 'required|email',
        'partner_sign'      => 'required|exists:partners,sign',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    // 如果未设置 默认是蛇形复数形式的表明
    protected $table = 'partner_admin_users';

    /** ============== JWT 实现 ================ */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }


    // 后端用户列表
    public function getAdminSel($admin_id){
        if($admin_id){
            $res = self::select('id', 'username')->where("id", $admin_id)->first();
            return $res['username'];
        }
        return self::select('id', 'username')->where("status", 1)->get();
    }

    /**
     * 获取与用户关联的电话号码
     */
    public function group()
    {
        return PartnerAdminGroup::find($this->group_id);
    }

    /**
     * 获取所有合作伙伴
     * @param $condition
     * @param int $pageSize
     * @return array
     */
    static function getAdminUserList($condition, $pageSize = 20) {
        $query = self::select(
            DB::raw('partner_admin_users.*'),
            DB::raw('partners.name as partner_name')
        )->leftJoin('partners', 'partners.sign', '=', 'partner_admin_users.partner_sign')->orderBy('id', 'desc');

        $currentPage    = isset($condition['pageIndex']) ? intval($condition['pageIndex']) : 1;
        $pageSize       = isset($condition['pageSize']) ? intval($condition['pageSize']) : $pageSize;
        $offset         = ($currentPage - 1) * $pageSize;

        $total  = $query->count();
        $data   = $query->skip($offset)->take($pageSize)->get();

        return ['data' => $data, 'total' => $total, 'currentPage' => $currentPage, 'totalPage' => intval(ceil($total / $pageSize))];
    }

    // 获得直接下级组
    public function getChildGroup() {
        if ($this->id == 1) {
            $groups = PartnerAdminGroup::where("pid", 1)->get();
        } else {
            $groups = PartnerAdminGroup::where("pid", $this->group_id)->get();
        }

        $data = [];
        if ($groups) {
            foreach($groups as $g) {
                $data[$g->id] = $g->name;
            }
        }

        return $data;
    }

    // 活动所有下级
    public function getChildGroupAll() {
        $groups = PartnerAdminGroup::where("rid", 'like', $this->group_id  ."|%")->get();
        $_l = substr_count($this->group_id, '|');
        $data = [];
        if ($groups) {
            foreach($groups as $g) {
                $_k = substr_count($g->rid, '|');
                $_i = $_k - $_l - 1;
                $str = "";
                if ($_i > 0) {
                    for($j = 0; $j < $_i; $j ++) {
                        $str .= "&nbsp;&nbsp;&nbsp;";
                    }
                    for($j = 0; $j < $_i; $j ++) {
                        $str .= "--";
                    }
                }
                $data[$g->id] = $str . $g->name;
            }
        }

        return $data;
    }

    /**
     * 添加管理员
     * @param $data
     * @param $adminUser
     * @return bool|string
     */
    public function saveItem($data, $adminUser) {

        $validator  = Validator::make($data, $this->rules);

        if ($validator->fails()) {
            return $validator->errors()->first();
        }

        if (!$this->id) {
            $res = self::checkPassword($data['password']);
            if ($res !== true) {
                return $res;
            }

            $res = self::checkFundPassword($data['fund_password']);
            if ($res !== true) {
                return $res;
            }
        }

        // 邮箱 不能重复
        if (!$this->id) {
            $count = self::where('email', '=', $data['email'])->count();
            if ($count > 0) {
                return "对不起, 邮箱(email)已经存在!!";
            }
        } else {
            $count = self::where('email', '=', $data['email'])->where("id", "<>", $this->id)->count();
            if ($count > 0) {
                return "对不起, 邮箱(email)已经存在!!";
            }
        }

        // 用户名 不能重复
        if (!$this->id) {
            $count = self::where('username', '=', $data['username'])->count();
            if ($count > 0) {
                return "对不起, 用户名(username)已经存在!!";
            }
        } else {
            $count = self::where('username', '=', $data['username'])->where("id", "<>", $this->id)->count();
            if ($count > 0) {
                return "对不起, 用户名(username)已经存在!!";
            }
        }

        $this->username         = $data['username'];
        $this->partner_sign     = $data['partner_sign'];
        $this->email            = $data['email'];
        $this->register_ip      = real_ip();
        $this->status           = 1;

        if (!$this->id) {
            $this->password         = bcrypt($data['password']);
            $this->fund_password    = bcrypt($data['fund_password']);
        }

        $this->add_admin_id = $adminUser ? $adminUser->id : 999999;
        $this->save();

        return $this;
    }

    /**
     * 密码检测
     * @param $password
     * @return bool|string
     */
    static function checkPassword($password) {
        if (!preg_match("/^[0-9a-zA-Z_]{6,16}$/i", $password) || preg_match("/^[0-9]+$/", $password) || preg_match("/^[a-zA-Z]+$/i", $password) || preg_match("/(.)\\1{2,}/i", $password)) {
            return "对不起, 密码输入格式不正确!";
        } else {
            return true;
        }
    }

    /**
     * 密码检测
     * @param $password
     * @return bool|string
     */
    static function checkFundPassword($password) {
        if (!preg_match("/^[0-9a-zA-Z]{6,16}$/", $password)) {
            return "对不起, 资金密码格式不正确!";
        } else {
            return true;
        }
    }

    // 获取所有后台管理用户
    static function getAdminUserOptions() {
        $users = self::all();
        $options = [];
        foreach ($users as $user) {
            $options[$user->id] = $user->username;
        }
        return $options;
    }
}
