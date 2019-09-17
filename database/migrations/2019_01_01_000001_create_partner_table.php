<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

// 商户管理相关
class CreatePartnerTable extends Migration
{

    public function up()
    {
        // 商户列表
        Schema::create('partners', function (Blueprint $table) {
            $table->increments('id');

            $table->string('name',  64);
            $table->string('sign',     32);
            $table->string('theme', 32)->default('default');
            $table->string('remark',  128);

            // 娱乐城　配置
            $table->string('casino_username',  128);
            $table->string('casino_key',  128);

            // logo 图片
            $table->string('logo_image',  128)->default('');

            // 商户接入key
            $table->string('api_key',  128);

            // 绑定的直属
            $table->string('top_player_username',  64);
            $table->integer('top_player_id');

            //　变更人
            $table->integer('add_admin_id')->default(0);
            $table->integer('update_admin_id')->default(0);

            // 1 正常 0　维护
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
        });

        // 绑定域名
        Schema::create('partner_domain', function (Blueprint $table) {
            $table->increments('id');

            $table->string('partner_sign',  64);
            $table->string('name',          64);
            $table->string('domain',        128);
            $table->tinyInteger('type')->default(1);

            $table->string('remark', 128)->default('');

            $table->integer('add_admin_id')->default(0);
            $table->integer('update_admin_id')->default(0);

            $table->tinyInteger('status')->default(1);
            $table->timestamps();
        });

        // 商户配置
        Schema::create('partner_configures', function (Blueprint $table) {
            $table->increments('id');
            $table->string('partner_sign', 64)->comment('商户标识');

            $table->integer('pid')->comment('父类id, 顶级为0');
            $table->string('sign', 64)->comment('sign 标识');
            $table->string('name', 64)->comment('标题');
            $table->string('description',   128)->nullable()->comment('描述');
            $table->string('value',         128)->comment('配置选项value');

            $table->integer('add_partner_admin_id')->default(0)->comment('添加人, 系统添加为0');
            $table->integer('update_partner_admin_id')->default(0)->comment('上次更改人id');

            $table->tinyInteger('status')->default(0)->comment('0 禁用 1 启用');

            $table->timestamps();

            $table->index('sign');
            $table->index('partner_sign');
        });

        // 商户段 = 管理用户
        Schema::create('partner_admin_users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('partner_sign', 64)->comment('商户标识');

            $table->string('username',  64);
            $table->string('email',     64);
            $table->string('password', 64);
            $table->string('fund_password', 64);

            $table->string('remember_token', 64)->default('');

            // 数据
            $table->char('register_ip',     15);
            $table->char('last_login_ip',   15)->default('');

            $table->integer('last_login_time')->default(0);

            $table->integer('add_admin_id')->default(0);
            $table->integer('add_partner_admin_id')->default(0);
            $table->integer('update_admin_id')->default(0);
            $table->integer('update_partner_admin_id')->default(0);

            $table->tinyInteger('status')->default(1);

            $table->timestamps();

            $table->index(['partner_sign']);
        });

        // 商户 = 可用菜单配置
        Schema::create('partner_menu_config', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('pid');
            $table->string('rid',64)->default("");

            // 菜单
            $table->string('title',         64);
            $table->string('route',         64);

            $table->string('api_path',     64)->default("");

            // 整形
            $table->integer('sort')->default(0);
            $table->string('css_class',     64)->default("");

            // 1 菜单 2 链接
            $table->tinyInteger('type')->default(1);
            $table->tinyInteger('level')->default(1);
            $table->tinyInteger('status')->default(1);

            $table->integer('add_admin_id')->default(0);
            $table->integer('update_admin_id')->default(0);

            $table->timestamps();
        });

        // 商户 = 管理菜单
        Schema::create('partner_menus', function (Blueprint $table) {
            $table->increments('id');

            $table->string('partner_sign', 64)->comment('商户标识');
            $table->integer('menu_id');

            $table->tinyInteger('status')->default(1);

            $table->integer('add_admin_id')->default(0);
            $table->integer('update_admin_id')->default(0);

            // 商户自己修改
            $table->integer('add_partner_admin_id')->default(0);
            $table->integer('update_partner_admin_id')->default(0);

            $table->timestamps();

            $table->index(['partner_sign']);
        });

        // 商户 = 管理组
        Schema::create('partner_admin_groups', function (Blueprint $table) {
            $table->increments('id');

            $table->string('partner_sign', 64)->comment('商户标识');

            $table->string('name', 32);
            $table->text('acl');

            $table->string('remark',64);

            $table->tinyInteger('is_super_group')->default(0);

            $table->integer('add_admin_id')->default(0);

            // 商户自己修改
            $table->integer('add_partner_admin_id')->default(0);
            $table->integer('update_partner_admin_id')->default(0);

            $table->tinyInteger('status')->default(1);
            $table->timestamps();

            $table->index(['partner_sign']);
        });

        // 商户 = 管理组 - 用户
        Schema::create('partner_admin_group_users', function (Blueprint $table) {
            $table->increments('id');

            $table->string('partner_sign', 64)->comment('商户标识');

            $table->integer('group_id');
            $table->integer('partner_admin_id');

            $table->integer('add_admin_id')->default(0);
            $table->integer('update_admin_id')->default(0);

            $table->tinyInteger('status')->default(1);

            $table->timestamps();
            $table->index(['partner_sign']);
        });

        // 商户管理员访问日志
        Schema::create('partner_admin_access_logs', function (Blueprint $table) {
            $table->increments('id');

            $table->string('partner_sign', 64)->comment('商户标识');

            $table->integer('partner_admin_id')->default(0);
            $table->string('partner_admin_username', 64)->default("");

            $table->string('route',     64);
            $table->char('ip',          15);
            $table->text('params');

            $table->string('device', 64)->default('');
            $table->string('platform', 64)->default('');
            $table->string('browser', 64)->default('');
            $table->string('agent', 256)->default('');

            $table->integer('add_time');

            $table->integer('day');
            $table->timestamps();

            $table->index(['partner_sign']);
            $table->index(['ip', 'partner_sign']);
            $table->index(['partner_admin_id', 'partner_sign'], 'admin_p');
            $table->index(array("partner_admin_username", "partner_sign"), "p_admin_user");
            $table->index(array("partner_sign", "add_time"), "p_add_time");
        });

        // 商户 管理员　行为
        Schema::create('partner_admin_behavior', function (Blueprint $table) {
            $table->increments('id');

            $table->string('partner_sign', 64)->comment('商户标识');
            $table->integer('partner_admin_id');
            $table->string('partner_admin_username', 64);

            $table->string('type', 64);

            $table->text('content');

            $table->char('ip', 15)->default('');

            $table->integer('add_time');

            $table->string('device', 64)->default('');
            $table->string('platform', 64)->default('');
            $table->string('browser', 64)->default('');
            $table->string('agent', 256)->default('');

            $table->timestamps();
            $table->index(['partner_sign']);
            $table->index(['partner_admin_id',  'partner_sign'], "admin_p");
            $table->index(array("partner_sign", 'partner_admin_username', 'add_time'), "p_admin_add_time");
            $table->index(array("partner_sign", "type", 'add_time'), 'p_type_add_time');
        });

        // 商户　管理员　动作审核
        Schema::create('partner_admin_action_review', function (Blueprint $table) {
            $table->increments('id');

            $table->string('partner_sign', 64)->comment('商户标识');

            $table->integer('player_id');
            $table->string('player_username',     64);

            // 处理类型
            $table->string('type', 32);

            // 配置 / 描述
            $table->string('process_config', 128);
            $table->string('process_desc', 128);

            // ip
            $table->char('request_ip',      15);
            $table->char('review_ip',       15)->default('');

            // 时间
            $table->integer('request_time')->default(0);
            $table->integer('review_time')->default(0);

            // 添加人员
            $table->integer('request_admin_id')->default(0);
            $table->string('request_admin_name', 64)->default('');

            // 审核人员
            $table->integer('review_admin_id')->default(0);
            $table->string('review_admin_name', 64)->default('');

            // 审核失败原因
            $table->string('review_fail_reason', 64)->default('');

            // 当前状态
            $table->tinyInteger('status')->default(0);

            $table->timestamps();

            $table->index(["partner_sign"], 'partner_sign');
            $table->index(["player_id", "partner_sign"]);
            $table->index(["player_username", "partner_sign"]);
        });

        // 绑定域名
        Schema::create('partner_casino_platform', function (Blueprint $table) {
            $table->increments('id');

            $table->string('partner_sign',      64);
            $table->string('platform_code',     64);

            $table->tinyInteger('status')->default(1);

            $table->integer('add_admin_id')->default(999999);
            $table->integer('update_admin_id')->default(0);

            $table->integer('update_partner_admin_id')->default(0);
            $table->timestamps();
        });

        // 商户 用户级别 title
        Schema::create('partner_player_level_title', function (Blueprint $table) {
            $table->increments('id');
            $table->string('partner_sign', 64)->comment('商户标识');
            $table->integer('level');
            $table->string('title', 64)->default('');
            $table->string('remark', 64)->default('');
            $table->timestamps();
            $table->index(['partner_sign']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('partners');
        Schema::dropIfExists('partner_menus');
        Schema::dropIfExists('partner_configures');
        Schema::dropIfExists('partner_domain');
        Schema::dropIfExists('partner_casino_platform');
        Schema::dropIfExists('partner_admin_users');
        Schema::dropIfExists('partner_admin_groups');
        Schema::dropIfExists('partner_admin_group_users');
        Schema::dropIfExists('partner_admin_access_logs');
        Schema::dropIfExists('partner_admin_behavior');
        Schema::dropIfExists('partner_admin_action_review');


    }
}
