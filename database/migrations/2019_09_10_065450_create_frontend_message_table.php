<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFrontendMessageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('frontend_info_categories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title',45)->nullable()->comment('标题');
            $table->integer('parent')->nullable()->comment('父级id');
            $table->string('template',45)->nullable()->comment('模板');
            $table->integer('platform_id')->nullable()->comment('平台id （system_platforms表id）');
            $table->nullableTimestamps();
            $table->engine = 'InnoDB';
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';
        });

        Schema::create('frontend_message_notices', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('receive_user_id')->comment('接收的用户id');
            $table->integer('notices_content_id')->nullable()->comment('消息表id（frontend_message_notices_contents）');
            $table->tinyInteger('status')->comment('0未读  1已读');
            $table->nullableTimestamps();
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });

        Schema::create('frontend_message_notices_contents', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('operate_admin_id')->nullable()->comment('发送信息的管理员id');
            $table->string('operate_admin_name',32)->nullable()->comment('发送信息的管理员name');
            $table->tinyInteger('type')->nullable()->comment('1公告 2站内信');
            $table->string('title',45)->nullable()->comment('标题');
            $table->text('content')->nullable()->comment('内容');
            $table->timestamp('start_time')->nullable()->comment('开始时间');
            $table->timestamp('end_time')->nullable()->comment('结束时间');
            $table->text('pic_path')->nullable();
            $table->integer('sort')->nullable();
            $table->string('describe',30)->comment('公告简介');
            $table->unsignedTinyInteger('status')->comment('1显示 0关闭');
            $table->nullableTimestamps();
            $table->engine = 'InnoDB';
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';
        });

        Schema::create('frontend_users_help_centers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('pid')->comment('上级id');
            $table->string('menu',32)->comment('标题');
            $table->text('content')->nullable()->comment('内容');
            $table->tinyInteger('status')->default(0)->comment('开启状态 0关闭 1开启');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
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
