<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades;

class CreateBackendAdminUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('backend_admin_access_groups', function (Blueprint $table) {
            $table->increments('id');
            $table->string('group_name', 15)->collation('utf8_general_ci')->comment('管理员组名称');
            $table->text('role')->nullable()->collation('utf8_general_ci')->comment('管理员组权限');
            $table->unsignedInteger('status')->nullable()->default(1)->comment('状态');
            $table->unsignedInteger('platform_id')->default(1)->nullable()->comment('平台id');
            $table->unique(['group_name', 'platform_id'],'group_name');
            $table->index('platform_id','fk_partner_access_platform_id_idx');
            $table->timestamp('created_at')->default(Facades\DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->useCurrent();
            $table->engine = 'InnoDB';
            $table->charset = 'utf8';
            $table->collation = 'utf8_unicode_ci';
        });

        Schema::create('backend_admin_audit_passwords_lists', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('type')->comment('审核类型 1=password, 2=资金密码');
            $table->unsignedInteger('user_id')->comment('被审核用户的id');
            $table->text('audit_data')->collation('utf8_general_ci')->comment('待审核的数据');
            $table->tinyInteger('status')->default(0)->comment('0:审核中, 1:审核通过, 2:审核拒绝');
            $table->integer('audit_flow_id')->nullable()->comment('提交人 与审核人的记录流程');
            $table->nullableTimestamps();
            $table->engine = 'InnoDB';
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';
        });

        Schema::create('backend_admin_users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name',64)->comment('管理员名称');
            $table->string('email',255)->comment('邮箱');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password',255)->comment('密码');
            $table->text('remember_token')->nullable()->comment('token');
            $table->tinyInteger('is_test')->nullable()->default(0)->comment('是否测试号   0不是 1是');
            $table->integer('group_id')->nullable()->unsigned()->comment('管理员组id');
            $table->unsignedInteger('status')->nullable()->default(1)->comment('状态 0关闭 1开启');
            $table->unsignedInteger('platform_id')->nullable()->comment('平台id');
            $table->unsignedInteger('super_id')->nullable();
            $table->index(['platform_id', 'status'],'fk_platform_id_status');
            $table->index('group_id','backend_admin_users_group_id_fk_idx');
            $table->index('super_id','backend_admin_users_super_id_foreign');
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
