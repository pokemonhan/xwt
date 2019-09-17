<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommonSystem extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('system_address_ips', function (Blueprint $table) {
            $table->increments('id');
            $table->string('ip',16)->nullable();
            $table->string('country',64)->nullable()->comment('国家');
            $table->string('region',64)->nullable()->comment('省份');
            $table->string('city',64)->nullable()->comment('城市');
            $table->string('county',64)->nullable()->comment('县');
            $table->nullableTimestamps();
            $table->engine = 'InnoDB';
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';
        });

        Schema::create('system_configurations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('parent_id')->nullable()->default(0)->comment('父级id');
            $table->integer('pid')->comment('父类id, 顶级为0父类id, 顶级为0');
            $table->string('sign',32)->comment('sign 标识');
            $table->string('name',32)->comment('标题');
            $table->string('description',128)->nullable()->comment('描述');
            $table->string('value',128)->nullable()->comment('配置选项value');
            $table->integer('add_admin_id')->default(0)->comment('添加人, 系统添加为0');
            $table->integer('last_update_admin_id')->default(0)->comment('上次更改人id');
            $table->tinyInteger('status')->default(0)->comment('0 禁用 1 启用');
            $table->tinyInteger('display')->nullable()->default(1)->comment('是否显示 0不显示 1显示');
            $table->index('sign','sys_configures_sign_index');
            $table->nullableTimestamps();
            $table->engine = 'InnoDB';
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';
        });

        Schema::create('system_platforms', function (Blueprint $table) {
            $table->increments('platform_id');
            $table->string('platform_name',20);
            $table->string('platform_sign',20);
            $table->unsignedInteger('status')->nullable()->default(1);
            $table->longText('comments');
            $table->integer('prize_group_min')->nullable()->default(1700);
            $table->integer('prize_group_max')->nullable()->default(1980);
            $table->integer('single_price')->nullable()->default(2);
            $table->string('open_mode',255)->nullable()->default('1|0.1|0.01');
            $table->integer('admin_id')->nullable();
            $table->integer('last_admin_id')->nullable();
            $table->index(['platform_id', 'status'],'ID');
            $table->nullableTimestamps();
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });

        Schema::create('users_regions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('region_id',16)->nullable()->comment('行政编码');
            $table->string('region_parent_id',16)->nullable()->comment('父级行政编码');
            $table->string('region_name',64)->nullable()->comment('名称');
            $table->tinyInteger('region_level')->nullable()->comment('1.省 2.市(市辖区)3.县(区、市)4.镇(街道)');
            $table->nullableTimestamps();
            $table->engine = 'InnoDB';
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';
        });

        Schema::create('frontend_system_ads_types', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name',32)->nullable()->comment('名称');
            $table->tinyInteger('type')->nullable()->comment('类型');
            $table->tinyInteger('status')->nullable()->comment('状态 0关闭 1开启');
            $table->tinyInteger('ext_type')->nullable()->comment('1图片 2视频 3广告');
            $table->integer('l_size')->nullable()->comment('长度');
            $table->integer('w_size')->nullable()->comment('宽度');
            $table->integer('size')->nullable()->comment('大小');
            $table->nullableTimestamps();
            $table->engine = 'InnoDB';
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';
        });

        Schema::create('frontend_system_banks', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title',45)->nullable()->comment('标题');
            $table->string('code',45)->nullable()->comment('code');
            $table->tinyInteger('pay_type')->nullable()->comment('1银行卡 2微信 3支付宝 之类');
            $table->tinyInteger('status')->nullable()->comment('状态 0关闭 1开启');
            $table->decimal('min_recharge',10,2)->nullable()->comment('最小充值金额');
            $table->decimal('max_recharge',10,2)->nullable()->comment('最大充值金额');
            $table->decimal('min_withdraw',10,2)->nullable()->comment('最小提现金额');
            $table->decimal('max_withdraw',10,2)->nullable()->comment('最大提现金额');
            $table->string('remarks',128)->nullable()->comment('描述');
            $table->string('allow_user_level',45)->nullable()->comment('用户层级 1-10');
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
