<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

// 活动相关
class CreateActivityTable extends Migration
{

    public function up()
    {
        // 活动列表
        Schema::create('activity_list', function (Blueprint $table) {
            $table->increments('id');
            $table->string('partner_sign', 32);

            // 活动类型
            $table->integer('type')->default(1);

            $table->string('title',  64);
            $table->text('content')->nullable();

            $table->integer('start_time');
            $table->integer('end_time');

            $table->string('image_home',    128)->nullable();
            $table->string('image_list',    128)->nullable();
            $table->string('image_content', 128)->nullable();

            $table->string('image_app_home',    128)->nullable();
            $table->string('image_app_list',    128)->nullable();
            $table->string('image_app_content', 128)->nullable();

            $table->tinyInteger('is_home')->default(1);
            $table->tinyInteger('is_hot')->default(1);

            $table->string('link', 128);

            // 1 通用 2 礼金 3 积分
            $table->tinyInteger('join_currency_type')->default(1);

            // 1 通用 2 礼金 3 积分
            $table->tinyInteger('bonus_currency_type')->default(1);

            //　创建人
            $table->integer('add_admin_id')->default(0);
            $table->integer('update_admin_id')->default(0);

            // 1 正常 0　维护
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
        });

        // 幸运大转盘配置
        Schema::create('activity_lucky_wheel_config', function (Blueprint $table) {
            $table->increments('id');
            $table->string('partner_sign', 32);

            // 活动ID
            $table->integer('aid');

            // 转动需求 1 投注量 2 充值量
            $table->integer('draw_need_type')->default(1);
            $table->integer('draw_need_bet_amount')->default(0);
            $table->integer('draw_need_recharge_amount')->default(0);

            // 配置 level => 1, bonus => 5, probability => 10, slot => 12
            $table->mediumText('level_config');

            //　创建人
            $table->integer('add_admin_id')->default(0);
            $table->integer('update_admin_id')->default(0);

            // 1 正常 0　维护
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
        });

        // 签到配置
        Schema::create('activity_sign_in_config', function (Blueprint $table) {
            $table->increments('id');
            $table->string('partner_sign', 32);

            // 活动ID
            $table->integer('aid');

            // 天
            $table->integer('day')->default(0);
            $table->integer('bonus')->default(0);
            $table->integer('need_bet_amount')->default(0);
            $table->integer('need_recharge_amount')->default(0);

            //　创建人
            $table->integer('add_admin_id')->default(0);
            $table->integer('update_admin_id')->default(0);

            // 1 正常 0　维护
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
        });

        // 首冲配置
        Schema::create('activity_first_recharge_config', function (Blueprint $table) {
            $table->increments('id');
            $table->string('partner_sign', 32);

            // 活动ID
            $table->integer('aid');

            // 天
            $table->integer('recharge_amount')->default(0);
            $table->integer('need_bet_amount')->default(0);
            $table->integer('bonus_percentage')->default(0);

            //　创建人
            $table->integer('add_admin_id')->default(0);
            $table->integer('update_admin_id')->default(0);

            // 1 正常 0　维护
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
        });

        // 节日送配置
        Schema::create('activity_festival_send_config', function (Blueprint $table) {
            $table->increments('id');
            $table->string('partner_sign', 32);

            // 活动ID
            $table->integer('aid');

            $table->integer('level')->default(0);
            $table->integer('bonus_min')->default(0);
            $table->integer('bonus_max')->default(0);
            $table->integer('need_total_bet_amount')->default(0);

            //　创建人
            $table->integer('add_admin_id')->default(0);
            $table->integer('update_admin_id')->default(0);

            // 1 正常 0　维护
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
        });

        // 加奖配置
        Schema::create('activity_prize_plus_config', function (Blueprint $table) {
            $table->increments('id');
            $table->string('partner_sign', 32);

            // 活动ID
            $table->integer('aid');

            $table->integer('bonus')->default(0);
            $table->integer('need_bet_amount')->default(0);
            $table->integer('need_recharge_amount')->default(0);
            $table->integer('need_score_amount')->default(0);

            //　创建人
            $table->integer('add_admin_id')->default(0);
            $table->integer('update_admin_id')->default(0);

            // 1 正常 0　维护
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
        });

        // 幸运宝箱 配置 转换礼金 通用和积分
        Schema::create('activity_luck_box_config', function (Blueprint $table) {
            $table->increments('id');
            $table->string('partner_sign', 32);

            // 活动ID
            $table->integer('aid');

            // 宝箱类型 1 金子 2 银子 3 铜 4 铁包厢
            $table->integer('type')->default(0);
            $table->integer('bonus')->default(0);
            $table->integer('bonus_currency_type')->default(3);
            $table->integer('slot')->default(1);

            //　创建人
            $table->integer('add_admin_id')->default(0);
            $table->integer('update_admin_id')->default(0);

            // 1 正常 0　维护
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('activity_list');
        Schema::dropIfExists('activity_lucky_wheel_config');
        Schema::dropIfExists('activity_sign_in_config');
        Schema::dropIfExists('activity_first_recharge_config');
        Schema::dropIfExists('activity_festival_send_config');

        Schema::dropIfExists('activity_prize_plus_config');
        Schema::dropIfExists('activity_luck_box_config');
    }
}
