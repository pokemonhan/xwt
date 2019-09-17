<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades;

class CreateFrontendProject extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lottery_trace_lists', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('top_id');
            $table->integer('parent_id');
            $table->string('rid', 256);
            $table->integer('trace_id')->nullable();
            $table->integer('order_queue')->nullable();
            $table->string('series_id', 32)->comment('彩种系列标识');
            $table->integer('project_id')->nullable();
            $table->string('project_serial_number', 64)->nullable()->comment('投注单号');
            $table->string('issue', 32)->comment('奖期号');
            $table->string('username', 64)->comment('用户名');
            $table->tinyInteger('is_tester')->default(0);
            $table->string('lottery_sign', 32)->comment('彩种标识');
            $table->string('method_sign', 32)->comment('玩法标识');
            $table->string('method_group', 32)->comment('玩法组');
            $table->string('method_name', 32)->comment('玩法中文名');
            $table->longText('bet_number')->comment('下注号码');
            $table->integer('times')->default(1)->comment('倍数');
            $table->decimal('single_price', 15, 4)->comment('单注金额');
            $table->decimal('total_price', 15, 4)->comment('总下注金额');
            $table->decimal('mode', 15, 4)->default(1.0000)->comment('模式 （元：1.0000   角：0.1000   分0.0100）');
            $table->integer('user_prize_group')->comment('用户奖金组');
            $table->integer('bet_prize_group')->comment('彩票奖金组');
            $table->string('prize_set', 1024)->nullable()->comment('奖金设置');
            $table->char('ip', 15)->comment('ip');
            $table->string('proxy_ip', 20);
            $table->tinyInteger('bet_from')->default(1);
            $table->tinyInteger('status')->default(0)->comment('0 等待追号 1正在追号  2追号完成   3玩家撤销  4管理员撤销  5系统撤销  6中奖停止');
            $table->tinyInteger('finished_status')->default(0);
            $table->timestamp('cancel_time')->default(Facades\DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            $table->nullableTimestamps();
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });

        Schema::create('lottery_traces', function (Blueprint $table) {
            $table->increments('id');
            $table->string('trace_serial_number',64)->nullable();
            $table->integer('user_id');
            $table->integer('top_id');
            $table->integer('parent_id');
            $table->string('rid',256)->default('');
            $table->string('username',64)->default('');
            $table->tinyInteger('is_tester')->default(0);
            $table->string('series_id',32)->comment('彩种系列标识');
            $table->string('lottery_sign',32)->comment('彩种标识');
            $table->string('method_sign',32)->comment('玩法标识');
            $table->string('method_group',32)->comment('玩法组');
            $table->string('method_name',32)->comment('玩法中文名');
            $table->longText('bet_number')->comment('下注号码');
            $table->integer('times')->default(1)->comment('倍数');
            $table->decimal('single_price',15,4)->comment('单注金额');
            $table->decimal('total_price',15,4)->comment('下注总金额');
            $table->tinyInteger('win_stop')->default(0)->comment('中奖后停止追号 （0否 1是）');
            $table->decimal('mode',15,4)->default(1.0000)->comment('模式 （元：1.0000   角：0.1000   分0.0100）');
            $table->integer('user_prize_group')->comment('用户奖金组');
            $table->integer('bet_prize_group')->comment('彩种奖金组');
            $table->string('prize_set',1024)->nullable()->comment('奖金设置');
            $table->integer('total_issues');
            $table->integer('finished_issues')->default(0);
            $table->integer('canceled_issues')->default(0);
            $table->integer('finished_amount')->default(0);
            $table->decimal('finished_bonus',18,4)->nullable();
            $table->integer('canceled_amount')->default(0);
            $table->string('start_issue',16)->comment('开始追号的奖期');
            $table->string('now_issue',16)->comment('现在追号的奖期');
            $table->string('end_issue',16)->comment('结束追号的奖期');
            $table->string('stop_issue',16)->comment('停止追号的奖期');
            $table->text('issue_process')->comment('追号详情');
            $table->integer('add_time')->comment('开始时间');
            $table->integer('stop_time')->comment('结束时间');
            $table->char('ip',15);
            $table->string('proxy_ip',200);
            $table->tinyInteger('bet_from')->default(1);
            $table->tinyInteger('status')->default(0)->comment('0 正在追号  1追号完成   2中奖停止  4系统撤销  5玩家撤销');
            $table->tinyInteger('finished_status')->default(0);
            $table->index('top_id','traces_top_id_index');
            $table->index('parent_id','traces_parent_id_index');
            $table->index('rid','traces_rid_index');
            $table->index('method_sign','traces_method_sign_index');
            $table->index('method_name','traces_method_name_index');
            $table->nullableTimestamps();
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });

        Schema::create('projects', function (Blueprint $table) {
            $table->increments('id');
            $table->string('serial_number',64)->nullable()->comment('投注订单号码');
            $table->integer('user_id');
            $table->string('username',64)->default('');
            $table->integer('top_id');
            $table->string('rid',256)->default('');
            $table->integer('parent_id');
            $table->tinyInteger('is_tester')->default(0);
            $table->string('series_id',32)->comment('彩种系列标识');
            $table->integer('basic_method_id')->nullable()->comment('lottery_basic_methods表id');
            $table->string('lottery_sign',32)->comment('彩种标识');
            $table->string('method_sign',32)->comment('玩法标识');
            $table->string('method_group',32)->comment('玩法组');
            $table->string('method_name',32)->comment('玩法中文名');
            $table->integer('user_prize_group')->comment('用户奖金组');
            $table->integer('bet_prize_group')->comment('彩票奖金组');
            $table->integer('trace_id')->default(0);
            $table->integer('level')->nullable();
            $table->decimal('mode',15,4)->comment('模式 （元：1.0000   角：0.1000   分0.0100）');
            $table->unsignedInteger('times')->comment('倍数');
            $table->decimal('price',15,4);
            $table->decimal('total_cost',15,4)->default(0.0000);
            $table->string('issue',32)->comment('奖期号');
            $table->text('bet_number')->comment('下注的号码');
            $table->string('open_number',64)->default('')->comment('开奖的号码');
            $table->string('winning_number',60)->nullable()->comment('中奖号码');
            $table->string('prize_set',1024)->comment('奖金设置');
            $table->tinyInteger('is_win')->default(0)->comment('是否中奖  （0否 1是）');
            $table->decimal('bonus',15,4)->default(0.0000)->comment('中奖金额');
            $table->decimal('point',15,4)->default(0.0000);
            $table->char('ip',15);
            $table->string('proxy_ip',200);
            $table->tinyInteger('bet_from')->default(1);
            $table->tinyInteger('status')->default(0)->comment('0已投注 1已撤销 2未中奖 3已中奖 4已派奖');
            $table->tinyInteger('status_input')->default(0);
            $table->tinyInteger('status_count')->default(0);
            $table->tinyInteger('status_prize')->default(0);
            $table->tinyInteger('status_commission')->default(0);
            $table->tinyInteger('status_trace')->default(0);
            $table->tinyInteger('status_stat')->default(0);
            $table->integer('time_bought')->default(0);
            $table->integer('time_input')->default(0);
            $table->integer('time_count')->default(0);
            $table->integer('time_prize')->default(0)->comment('派奖时间');
            $table->integer('commission_time')->default(0);
            $table->integer('time_trace')->default(0);
            $table->integer('time_cancel')->default(0);
            $table->integer('time_stat')->default(0);
            $table->index(['user_id', 'lottery_sign', 'time_bought'],'projects_user_id_lottery_sign_time_bought_index');
            $table->index(['lottery_sign', 'time_bought'],'projects_lottery_sign_time_bought_index');
            $table->index(['issue', 'user_id'],'projects_issue_user_id_index');
            $table->index('top_id','projects_top_id_index');
            $table->nullableTimestamps();
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
}
