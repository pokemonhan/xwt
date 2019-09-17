<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommonActivity extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('activity_bet_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('activity_id');
            $table->integer('config_id');
            $table->integer('user_id');
            $table->integer('username');
            $table->integer('day');
            $table->integer('level');
            $table->integer('bonus');
            $table->integer('fetched_time');
            $table->integer('current_bets')->default(0);
            $table->integer('current_recharge')->default(0);
            $table->integer('current_lose')->default(0);
            $table->char('ip',15);
            $table->tinyInteger('status')->default(1);
            $table->integer('checked_admin_id');
            $table->integer('checked_time');
            $table->nullableTimestamps();
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });

        Schema::create('frontend_activity_contents', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title',45)->nullable();
            $table->text('content')->nullable()->comment('内容');
            $table->string('pic_path',255)->nullable()->comment('图片路径');
            $table->string('preview_pic_path',255)->nullable()->comment('图标路径');
            $table->timestamp('start_time')->nullable()->comment('开始时间');
            $table->timestamp('end_time')->nullable()->comment('结束时间');
            $table->tinyInteger('status')->nullable()->comment('开启状态 0关闭 1开启');
            $table->integer('admin_id')->nullable()->comment('添加活动的管理员id （backend_admin_users表id）');
            $table->string('admin_name',45)->nullable()->comment('添加活动的管理员name （backend_admin_users表name）');
            $table->tinyInteger('is_redirect')->nullable()->comment('是否跳转 0否 1是');
            $table->string('redirect_url',128)->nullable()->comment('跳转地址');
            $table->tinyInteger('is_time_interval')->nullable()->comment('是否有期限  0永久 1有限');
            $table->integer('sort')->comment('排序');
            $table->tinyInteger('type')->comment('活动 属于哪端,1:网页端活动 ,2:手机端，3:全部展示');
            $table->nullableTimestamps();
            $table->engine = 'InnoDB';
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';
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
