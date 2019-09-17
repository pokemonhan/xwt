<?php
namespace App\Models\Stat;

use App\Models\BaseModel;
use Illuminate\Support\Facades\DB;

/**
 * 用户每日统计数据
 * Class UserStatDay
 * @package App\Models\Stat
 */
class UserStatDay extends BaseModel
{
    protected $table = 'user_stat_day';

    /**
     * 可变更　个人字段
     * @var array
     */
    public static $filters = array(
        'recharge_amount',
        'recharge_count',
        'recharge_first',
        'withdraw_amount',
        'withdraw_count',
        'system_transfer_in',
        'system_transfer_out',
        'parent_transfer_in',
        'salary',
        'dividend',
        'gift',
        'bets',
        'self_points',
        'child_points',
        'bonus',
        'canceled',
    );

    /**
     * 可变更团队数据
     * @var array
     */
    public static $teamFilters = array(
        'team_recharge_amount',
        'team_recharge_count',
        'team_withdraw_amount',
        'team_withdraw_count',

        'team_system_transfer_in',
        'team_system_transfer_out',
        'team_parent_transfer_in',

        'team_salary',
        'team_dividend',
        'team_gift',

        'team_bets',
        'team_self_points',
        'team_child_points',
        'team_bonus',
        'team_canceled',
    );

    /**
     * 获取列表
     * @param $c
     * @param int $pageSize
     * @return array
     */
    public static function getList($c, $pageSize = 15)
    {
        $query = self::orderBy('id', 'desc');

        // 用户名
        if (isset($c['username']) && $c['username']) {
            $query->where('username', $c['username']);
        }

        // 昵称
        if (isset($c['nickname']) && $c['nickname']) {
            $query->where('nickname', $c['nickname']);
        }

        // 用户ID
        if (isset($c['user_id']) && $c['user_id']) {
            $query->where('user_id', $c['user_id']);
        }

        // 上级ID
        if (isset($c['parent_id']) && $c['parent_id']) {
            $query->where('parent_id', $c['parent_id']);
        }

        // 是否机器人
        if (isset($c['is_robot']) && in_array($c['is_robot'], ["0", "1"]) && $c['is_robot'] != 'all') {
            $query->where('is_robot', $c['is_robot']);
        }

        // 日期 开始
        if (isset($c['start_day']) && $c['start_day']) {
            $query->where('day', ">=", date("Ymd", strtotime($c['start_day'])));
        } else {
            $query->where('day', ">=", date("Ymd"));
        }

        // 日期 结束
        if (isset($c['end_day']) && $c['end_day']) {
            $query->where('day', "<=", date("Ymd", strtotime($c['end_day'])));
        } else {
            $query->where('day', "<=", date("Ymd"));
        }

        $currentPage = isset($c['pageIndex']) ? intval($c['pageIndex']) : 1;
        $pageSize = isset($c['pageSize']) ? intval($c['pageSize']) : $pageSize;
        $offset = ($currentPage - 1) * $pageSize;

        $total = $query->count();
        $menus = $query->skip($offset)->take($pageSize)->get();

        return [
            'data' => $menus,
            'total' => $total,
            'currentPage' => $currentPage,
            'totalPage' => intval(ceil($total / $pageSize)),
        ];
    }

    /**
     * 初始化2天数据
     * @param $user
     * @return mixed
     */
    public static function initUserStatData($user)
    {
        $closeRobotData = configure("system_close_robot_stat", 0);
        if ($closeRobotData && $user->is_robot) {
            return true;
        }

        $startTime = time();
        $endTime = time() + 86400 * 1;
        $daySet = self::getDaySet($startTime, $endTime);

        $data = [];
        foreach ($daySet as $day) {
            $data[] = [
                'user_id' => $user->id,
                'top_id' => $user->top_id,
                'parent_id' => $user->parent_id,
                'username' => $user->username,
                'nickname' => $user->nickname,
                'day' => $day,
            ];
        }

        self::insert($data);

        // 初始化总
        $dataAll[] = [
            'user_id' => $user->id,
            'top_id' => $user->top_id,
            'parent_id' => $user->parent_id,
            'username' => $user->username,
            'nickname' => $user->nickname,
        ];

        UserStat::insert($dataAll);
        return true;
    }

    /**
     * 获取时间
     * @param $startTime
     * @param $endTime
     * @return array
     */
    public static function getDaySet($startTime, $endTime)
    {
        $daySet = [];

        while ($startTime <= $endTime) {
            $daySet[] = date("Ymd", $startTime);
            $startTime += 86400;
        }

        return $daySet;
    }

    /**
     * 变更团队字段
     * @param $data
     * @param $date
     * @return bool|string
     */
    public static function changeTeam($data, $date)
    {

        $date_day = date("Ymd", strtotime($date));

        foreach ($data as $userId => $item) {
            $teamUpdate = '';
            $teamAdd = '';
            foreach ($item as $field => $v) {
                if (!in_array($field, self::$teamFilters)) {
                    continue;
                }

                if (!$v) {
                    continue;
                }

                $teamUpdate .= $teamAdd . "`{$field}` = `{$field}` + {$v}";
                $teamAdd = ',';
            }

            // 更新自身量
            if ($teamUpdate) {
                $player = Player::find($userId);
                $rid = substr($player->rid, 0, strlen($player->rid) - 1);
                $ridArr = explode("|", $rid);

                array_pop($ridArr);

                if (!$ridArr) {
                    return true;
                }
                $rid = implode(',', $ridArr);

                $sql = "update `user_stat_day` set {$teamUpdate} where `user_id` in ({$rid})  and `day`='{$date_day}'";
                $ret = db()->update($sql);
                if (!$ret) {
                    Clog::statUser("stat-team-error-" . $player->id . "-更新-团队-失败-day-{$teamUpdate}");
                    return "更新失败!";
                }
            }
        }
        return true;
    }

    /**
     * 批量变更
     * @param $changes
     * @param $date
     * @param array $robotIds
     * @return array
     */
    public static function changeBatch($changes, $date, $robotIds = [])
    {
        $closeRobotData = configure("system_close_robot_stat", 0);
        $selfChange = [];

        foreach ($changes as $_userId => $_data) {
            if ($closeRobotData && $robotIds && in_array($_userId, $robotIds)) {
                continue;
            }

            foreach ($_data as $_f => $_v) {
                $selfChange[$_f][$_userId] = $_v;
            }
        }

        // 如果不存在 则返回
        if (!$selfChange) {
            return ['stat' => 0, 'stat_day' => 0];
        }

        $sqlStat = "update `user_stat` set ";
        $sqlStatDay = "update `user_stat_day` set ";

        // 拼接语句
        foreach ($selfChange as $__f => $_changeData) {
            $sqlStat .= "`" . $__f . "` = CASE ";
            $sqlStatDay .= "`" . $__f . "` = CASE ";
            foreach ($_changeData as $__userId => $__v) {
                $sqlStat .= " WHEN `user_id` = " . $__userId . " THEN `" . $__f . "` + " . $__v;
                $sqlStatDay .= " WHEN `user_id` = " . $__userId . " THEN `" . $__f . "` + " . $__v;
            }
            $sqlStat .= " ELSE `$__f` + 0 END,";
            $sqlStatDay .= " ELSE `$__f` + 0 END,";
        }

        $sqlStat = rtrim($sqlStat, ",") . " WHERE `id` > 0 ";
        $sqlStatDay = rtrim($sqlStatDay, ",") . " WHERE `day` = " . $date;

        $statCount = DB::update(DB::raw($sqlStat));
        $statDayCount = DB::update(DB::raw($sqlStatDay));

        return ['stat' => $statCount, 'stat_day' => $statDayCount];
    }

    /**
     * 数据变更写入
     * @param $user
     * @param $changes
     * @param $date
     * @return bool
     */
    public static function change($user, $changes, $date)
    {
        // 屏蔽机器人
        $closeRobotData = configure("system_close_robot_stat", 0);
        if ($closeRobotData && $user->is_robot) {
            return true;
        }

        $changes = array_intersect_key($changes, array_flip(self::$filters));
        if (empty($changes)) {
            return "无效的变更类型!";
        }

        $selfUpdate = '';
        $selfAdd = '';

        foreach ($changes as $field => $v) {
            $selfUpdate .= $selfAdd . "`{$field}` = `{$field}` + {$v}";
            $selfAdd = ',';
        }

        $date_day = date("Ymd", strtotime($date));

        // 更新自身量
        if ($selfUpdate) {
            $sqlDay = "update `user_stat` set {$selfUpdate}  where `user_id` ='{$user->id}'";
            $ret = db()->update($sqlDay);
            if (!$ret) {
                Clog::statUser("Error-" . $user->id . "-更新失败-all-{$selfUpdate}");
                return "更新失败!";
            }

            $sql = "update `user_stat_day` set {$selfUpdate} where `user_id` ='{$user->id}'  and `day`='{$date_day}'";
            $ret = db()->update($sql);
            if (!$ret) {
                Clog::statUser("Error-" . $user->id . "-更新失败-day-{$selfUpdate}");
                return "更新失败!";
            }
        }
        return true;
    }
}
