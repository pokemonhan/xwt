<?php

namespace App\Console\Commands;

use App\Models\User\FrontendUser;
use App\Models\User\FrontendUsersSpecificInfo;
use Config;
use Illuminate\Console\Command;

class SysUserAndSpecificIdControl extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'SysUserAndSpecificId';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '批量更新frontend_users 和 frontend_users_specific_infos 表中id同步 ';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $forntendUser = FrontendUser::all();
        foreach ($forntendUser as $k => $v) {
            $spec_id = $v->user_specific_id;
            //没有记录spec id
            if (is_null($spec_id) || empty($spec_id)) {
                //先查spec  表中是否有对应的user_id 的数据  有就回写到user 表中  没有就新建一条数据
                //    $spec = new FrontendUsersSpecificInfo();
                $spec = FrontendUsersSpecificInfo::where('user_id', $v->id)->first();
                //没有需要新建数据
                if (is_null($spec)) {
                    $spec = new FrontendUsersSpecificInfo();
                    $spec->user_id = $v->id;
                    $spec->save();
                    $v->update([
                        'user_specific_id' => $spec->id,
                    ]);
                }
                //已经有了回写user
                $v->update([
                    'user_specific_id' => $spec->id,
                ]);
            } else {
                //有spc_id 将spec表中的user_id 进行更改
                $spec = FrontendUsersSpecificInfo::find($spec_id);

                $spec->update([
                    'user_id' => $v->id,
                ]);
            }
        }
    }
}
