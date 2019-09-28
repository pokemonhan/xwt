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

        //后台活动列表
        Schema::create('backend_dyn_activity_lists', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name',200)->default('')->comment('活动名称');
            $table->string('uname',200)->default('')->comment('活动的别名,用于确定活动的处理器');
            $table->string('pc_pic',255)->default('')->comment('pc端活动的导入图');
            $table->string('wap_pic',)->default('')->comment('wap端活动的导入图');
            $table->string('rule_file',200)->default('')->comment('规则文件名');
            $table->timestamp('start_time')->useCurrent()->comment('活动开始时间');
            $table->timestamp('end_time')->useCurrent()->comment('活动结束时间');
            $table->tinyInteger('status')->default(0)->comment('活动的状态');
            $table->integer('sort')->default(0)->comment('排序');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrent();
            $table->index('name','backend_dyn_activity_lists_name');
            $table->engine = 'InnoDB';
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';
        });

        Schema::create('backend_dyn_activity_prizes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name',200)->default('')->comment('奖品名称');
            $table->integer('activity_id')->default(0)->comment('所属的活动id');
            $table->string('pic',255)->default('')->comment('奖品的图像');
            $table->decimal('value',20,2)->default(0.00)->comment('单个奖品的价值');
            $table->decimal('probability',20,2)->default(0.00)->comment('中奖的概率');
            $table->integer('amount')->default(0)->comment('奖品数量');
            $table->tinyInteger('is_entity')->default(0)->comment('是否是实体奖品');
            $table->integer('grade')->default(1)->comment('奖品等级');
            $table->integer('sort')->default(0)->comment('排序');
            $table->tinyInteger('status')->default(0)->comment('奖品的状态');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
            $table->index('name','backend_dyn_activity_prizes_name');
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
        Schema::dropIfExists('activity_bet_logs');
        Schema::dropIfExists('frontend_activity_contents');
        Schema::dropIfExists('backend_dyn_activity_lists');
        Schema::dropIfExists('backend_dyn_activity_prizes');
    }
}
