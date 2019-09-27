<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBackendAdminMessage extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('backend_admin_message_articles', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('category_id')->nullable()->comment('文章类型 （frontend_info_categories表id）');
            $table->string('title',45)->nullable()->comment('标题');
            $table->string('summary',45)->nullable()->comment('描述');
            $table->text('content')->nullable()->comment('内容');
            $table->string('search_text',45)->nullable()->comment('查询关键字');
            $table->tinyInteger('is_for_agent')->nullable()->comment('是否代理专属');
            $table->tinyInteger('status')->nullable()->comment('开启状态');
            $table->integer('audit_flow_id')->nullable()->comment('审核流程表id（backend_admin_audit_flow_lists表id）');
            $table->integer('add_admin_id')->nullable()->comment('添加文章的管理员id（backend_admin_users表id）');
            $table->integer('last_update_admin_id')->nullable()->comment('最后修改的管理员id（backend_admin_users表id）');
            $table->unsignedInteger('sort')->nullable()->comment('排序');
            $table->string('pic_path')->nullable()->comment('图片路径');
            $table->nullableTimestamps();
            $table->engine = 'InnoDB';
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';
        });

        Schema::create('backend_system_internal_messages', function (Blueprint $table) {
            $table->increments('id');
            $table->tinyInteger('operate_admin_id')->nullable()->comment('发送的管理员id null为系统 （backend_admin_users表id）');
            $table->integer('receive_admin_id')->nullable()->comment('接收的管理员id （（backend_admin_users表id））');
            $table->integer('receive_group_id')->nullable()->comment('接收的管理组id (backend_admin_access_groups表id)');
            $table->integer('message_id')->nullable()->comment('消息内容表id（notice_messages表 id）');
            $table->tinyInteger('status')->nullable()->comment('0未读 1已读');
            $table->nullableTimestamps();
            $table->engine = 'InnoDB';
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';
        });

        Schema::create('backend_system_notice_lists', function (Blueprint $table) {
            $table->increments('id');
            $table->tinyInteger('type')->nullable()->comment('1管理员手动发送的站内信   2审核相关的站内信  3充值提现相关的站内信');
            $table->text('message')->nullable()->comment('消息内容');
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
        Schema::dropIfExists('backend_admin_message_articles');
        Schema::dropIfExists('backend_system_internal_messages');
        Schema::dropIfExists('backend_system_notice_lists');
    }
}
