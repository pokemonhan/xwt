<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFrontendUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_public_avatars', function (Blueprint $table) {
            $table->increments('id');
            $table->string('pic_path',128)->collation('utf8_general_ci')->nullable()->comment('头像');
            $table->nullableTimestamps();
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_general_ci';
        });

        Schema::create('frontend_users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('username',64);
            $table->integer('top_id')->default(0)->nullable()->comment('最上级id');
            $table->integer('parent_id')->default(0)->nullable()->comment('上级id');
            $table->string('rid',256)->nullable();
            $table->integer('platform_id')->nullable();
            $table->string('sign',32)->comment('所属平台标识!');
            $table->integer('account_id')->nullable();
            $table->tinyInteger('type')->default(1)->comment('用户类型你:1 直属  2 代理 3 会员');
            $table->integer('vip_level')->nullable()->default(0)->comment('vip等级');
            $table->tinyInteger('is_tester')->nullable()->default(0);
            $table->tinyInteger('frozen_type')->nullable()->default(0)->comment('冻结类型:1, 禁止登录, 2, 禁止投注 3, 禁止提现,4禁止资金操作,5禁止投注');
            $table->string('password',64);
            $table->string('fund_password',64)->nullable();
            $table->integer('prize_group');
            $table->string('remember_token',100)->nullable();
            $table->integer('level_deep')->nullable()->default(0)->comment('用户等级深度');
            $table->char('register_ip',15);
            $table->char('last_login_ip',15)->nullable();
            $table->integer('register_time')->nullable();
            $table->timestamp('last_login_time')->nullable();
            $table->integer('user_specific_id')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->decimal('daysalary_percentage',3,1)->default(0.0)->comment('日工资比例');
            $table->tinyInteger('bonus_percentage')->default(0)->comment('分红比例');
            $table->string('pic_path',128)->nullable()->collation('utf8_general_ci')->comment('头像');
            $table->index(['sign', 'username'],'users_sign_username_index');
            $table->index(['sign', 'parent_id'],'users_sign_parent_id_index');
            $table->index(['sign', 'rid'],'users_sign_rid_index');
            $table->index(['sign', 'vip_level'],'users_sign_vip_level_index');
            $table->nullableTimestamps();
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });

        Schema::create('frontend_links_registered_users', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('register_link_id')->nullable()->comment('注册链接id（frontend_users_registerable_links表id）');
            $table->unsignedInteger('user_id')->comment('用户id （backend_admin_users表id）');
            $table->string('url',255)->comment('url内容');
            $table->string('username',16)->comment('用户名 （backend_admin_users表username）');
            $table->index('register_link_id','create_user_link_user_create_user_link_id_index');
            $table->index('user_id','create_user_link_user_user_id_index');
            $table->nullableTimestamps();
            $table->engine = 'InnoDB';
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';
        });

        Schema::create('frontend_users_privacy_flows', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('admin_id')->nullable()->comment('管理员id （backend_admin_users表id）');
            $table->string('admin_name',64)->collation('utf8_general_ci')->nullable()->comment('管理员名称 （backend_admin_users表name）');
            $table->integer('user_id')->nullable()->comment('用户id （frontend_users表id）');
            $table->string('username',64)->collation('utf8_general_ci')->nullable()->comment('用户名 （frontend_users表username）');
            $table->text('comment')->collation('utf8_general_ci')->nullable()->comment('内容');
            $table->nullableTimestamps();
            $table->engine = 'InnoDB';
            $table->charset = 'utf8';
            $table->collation = 'utf8_unicode_ci';
        });

        Schema::create('frontend_users_specific_infos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nickname',16)->nullable()->comment('昵称');
            $table->string('realname',16)->nullable()->comment('真实姓名');
            $table->string('mobile',11)->nullable()->comment('手机');
            $table->string('email',32)->nullable()->comment('邮箱');
            $table->string('zip_code',6)->nullable()->comment('邮编');
            $table->string('address',128)->nullable()->comment('详细地址');
            $table->tinyInteger('register_type')->default(0)->comment('注册类型：0.普通注册1.人工开户注册2.链接开户注册3.扫码开户注册');
            $table->integer('total_members')->nullable()->default(0)->comment('用户发展客户总数');
            $table->integer('user_id')->nullable()->comment('用户id');
            $table->timestamps();
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
