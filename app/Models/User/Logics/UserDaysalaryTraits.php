<?php

namespace App\Models\User\Logics;

trait UserDaysalaryTraits
{
    /**
     * 更新用户日工资
     * @param array $data 日工资数据.
     * @return array
     */
    public static function updateUserDaysalary(array $data)
    {
        if ($data['user_id'] && $data['date']) {
            $userDaysalary = self::where([
                ['user_id', $data['user_id']],
                ['date', $data['date']],
            ])->first();

            if (empty($userDaysalary)) {
                $userDaysalary = new self();
                $userDaysalary->fill($data);
                if ($saveStatus = $userDaysalary->save()) {
                    $logInfo = '新建用户日工资成功 '.json_encode($data);
                } else {
                    $logInfo = '新建用户日工资失败 '.json_encode($data);
                }
            } else {
                $userDaysalary->daysalary += $data['daysalary'] ;
                $userDaysalary->team_turnover += $data['team_turnover'];

                if ($saveStatus = $userDaysalary->save()) {
                    $logInfo = '更新用户日工资成功 '.json_encode($data);
                } else {
                    $logInfo = '更新用户日工资失败 '.json_encode($data);
                }
            }
            return ['success' => $saveStatus, 'log_info' => $logInfo];
        }
    }
}
