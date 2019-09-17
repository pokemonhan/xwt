<?php

namespace App\Models\Finance;

use App\Lib\Locker\AccountLocker;
use App\Lib\Clog;
use App\Lib\Moon\AccountChange;
use App\Models\Admin\Province;
use App\Models\Base;
use App\Models\User;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Validator;

class WithdrawLog extends Base
{
    public $rules = [
        'owner_name'        => 'required|min:2|max:128',
        'card_number'       => 'required|integer',
        'province'          => 'required|integer',
        'city'              => 'required|integer',
        'branch'            => 'required|min:4|max:128',
    ];

    protected $table = 'user_withdraw_log';

    static function getList($c, $pageSize = 20) {
        $query = self::orderBy('id', 'desc');

        // 用ID
        if (isset($c['user_id']) && $c['user_id']) {
            $query->where('user_id', $c['user_id']);
        }

        // 昵称
        if (isset($c['nickname']) && $c['nickname']) {
            $query->where('nickname', $c['nickname']);
        }

        // 用户名
        if (isset($c['username']) && $c['username']) {
            $query->where('username', $c['username']);
        }

        // 上级
        if (isset($c['back_status']) && $c['back_status'] && $c['back_status'] != 'all') {
            if (is_array($c['back_status'])) {
                $query->whereIn('back_status', $c['back_status']);
            } else {
                $query->where('back_status', $c['back_status']);
            }

        }

        // 上级
        if (isset($c['order_id']) && $c['order_id']) {
            $query->where('order_id', trim($c['order_id']));
        }

        // 时间
        if (isset($c['start_time']) && $c['start_time']) {
            $query->where('init_time', ">=", strtotime($c['start_time']));
        }

        // 时间
        if (isset($c['end_time']) && $c['end_time']) {
            $query->where('init_time', "<=", strtotime($c['end_time']));
        }

        $currentPage    = isset($c['pageIndex']) ? intval($c['pageIndex']) : 1;
        $pageSize       = isset($c['pageSize']) ? intval($c['pageSize']) : $pageSize;


        $offset         = ($currentPage - 1) * $pageSize;

        $total  = $query->count();
        $data  = $query->skip($offset)->take($pageSize)->get();

        return ['data' => $data, 'total' => $total, 'currentPage' => $currentPage, 'totalPage' => intval(ceil($total / $pageSize))];
    }

    // 保存
    static function initLog($user, $order, $params = "") {

        $model = new self();
        $model->ip                  = real_ip();
        $model->user_id             = $user->id;
        $model->top_id              = $user->top_id;
        $model->username            = $user->username;
        $model->nickname            = $user->nickname;
        $model->order_id            = $order->id;
        $model->amount              = $order->amount;

        $model->request_params      = $params ? json_encode($params) : '';
        $model->save();
        return $model;
    }

    static function getStatusDesc($status) {
        switch ($status) {
            case 0:
                return "<span>初始化</span>";
                break;
            case 1:
                return "<span style='color: green;'>提现成功</span>";
                break;
            case 2:
                return "<span style='color: red;'>提现失败</span>";
                break;
            default:
                return "未知状态";
        }
    }
}
