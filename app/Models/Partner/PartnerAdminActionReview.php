<?php

namespace App\Models\Partner;

use App\Models\Player\Player;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

/**
 * 商户管理员动作审核
 * Class PartnerAdminActionReview
 * @package App\Models\Partner
 */
class PartnerAdminActionReview extends Model
{
    protected $table = 'partner_admin_action_review';

    static $status = [
        0   => "待审核",
        1   => "审核中",
        2   => "审核成功",
        -2  => "人工失败",
        -3  => "条件失败",
    ];

    /**
     * 获取日志列表
     * @param $c
     * @return mixed
     */
    static function getList($c) {
        $query = self::orderBy('id', 'DESC');

        // 平台
        if (isset($c['partner_sign']) && $c['partner_sign'] && $c['partner_sign'] != "all") {
            $query->where('partner_sign', $c['partner_sign']);
        }

        // 用户名
        if (isset($c['type']) && $c['type'] && $c['type'] != "all") {
            $query->where('type', $c['type']);
        }

        // 审核管理员
        if (isset($c['review_admin_name']) && $c['review_admin_name']) {
            $query->where('review_admin_name', $c['review_admin_name']);
        }

        $currentPage    = isset($c['pageIndex']) ? intval($c['pageIndex']) : 1;
        $pageSize       = isset($c['pageSize']) ? intval($c['pageSize']) : 15;
        $offset         = ($currentPage - 1) * $pageSize;

        $total  = $query->count();
        $items  = $query->skip($offset)->take($pageSize)->get();

        return ['data' => $items, 'total' => $total, 'currentPage' => $currentPage, 'totalPage' => intval(ceil($total / $pageSize))];
    }

    // 处理
    public function process($adminUser) {
        $allTypes = config("admin.main.review_type", []);

        // 类型是否存在
        if (!array_key_exists($this->type, $allTypes)) {
            return "对不起, 无效的审核类型!";
        }

        // 玩家
        $player = Player::find($this->player_id);
        if (!$player) {
            return "对不起, 不存在的玩家!";
        }

        $config = unserialize($this->process_config);

        $status         = 2;
        $failReason     = "";
        switch($this->type) {
            case 'login_password':
                $player->password = Hash::make($config['new_password']);
                $player->save();
                break;
            case 'fund_password':
                $player->fund_password = Hash::make($config['new_password']);
                $player->save();
                break;
            case 'admin_transfer_to_player':
            case 'admin_reduce_from_player':
                $_adminUser = PartnerAdminUser::find($this->request_admin_id);
                $res =  $player->manualTransfer($config['mode'], $config['type'], $config['amount'], $config['desc'], $_adminUser);
                if (true !== $res) {
                    $failReason = $res;
                    $status     = -3;
                }
                break;
            case 'frozen':
                $player->frozen_type = $config['frozen_type'];
                $player->save();
                break;
            default:
                $failReason = "对不起, 无效的处理类型!";
                $status     = -3;
        }

        // 处理
        $this->review_admin_id      = $adminUser->id;
        $this->review_admin_name    = $adminUser->username;
        $this->review_ip            = real_ip();
        $this->review_time          = time();
        $this->status               = $status;
        $this->review_fail_reason   = $failReason;
        $this->save();
        return $status == 2 ? true : $failReason;
    }

    // 添加
    static function addReview($user, $type, $config, $adminUser) {

        $allTypes = config("admin.main.review_type", []);

        // 类型是否存在
        if (!array_key_exists($type, $allTypes)) {
            return "对不起, 无效的审核类型!";
        }

        $query = new self();
        $query->player_id           = $user->id;
        $query->player_username     = $user->username;
        $query->process_config      = serialize($config);
        $query->process_desc        = isset($config['desc']) ? $config['desc'] : "-";
        $query->type                = $type;
        $query->request_ip          = real_ip();
        $query->request_time        = time();

        $query->request_admin_id      = $adminUser->id;
        $query->request_admin_name    = $adminUser->username;

        $query->save();
        return true;
    }
}
