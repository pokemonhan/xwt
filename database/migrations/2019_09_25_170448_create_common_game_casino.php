<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommonGameCasino extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('casino_game_api_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('api',64)->comment('接口地址');
            $table->integer('user_id')->comment('请求接口的用户id');
            $table->string('call_url',256)->comment('请求的地址');
            $table->string('username',64)->comment('请求接口的用户名称');
            $table->string('ip',64);
            $table->text('params')->comment('请求的参数');
            $table->text('return_content')->comment('返回的数据');
            $table->nullableTimestamps();
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });

        Schema::create('casino_game_categories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name',64)->default('')->comment('类型名字');
            $table->string('code',64)->comment('类型code');
            $table->tinyInteger('home')->default(0)->comment('是否推荐 1推荐 0 不推荐');
            $table->nullableTimestamps();
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });

        Schema::create('casino_game_platforms', function (Blueprint $table) {
            $table->increments('id');
            $table->string('main_game_plat_name',64)->comment('平台名称');
            $table->string('main_game_plat_code',64)->comment('平台code');
            $table->decimal('rate',5,4)->nullable()->comment('费率');
            $table->tinyInteger('status')->default(1)->comment('1显示 0隐藏');
            $table->nullableTimestamps();
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });

        // 游戏列表
        Schema::create('casino_game_lists', function (Blueprint $table) {
            $table->increments('id');
            $table->string('main_game_plat_code',64)->comment('所述平台');
            $table->string('cn_name',128)->comment('中文名字');
            $table->string('en_name',128)->default('')->comment('英文名字');
            $table->string('pc_game_code',64)->nullable()->comment('pc游戏code');
            $table->string('pc_game_deputy_code',64)->nullable()->comment('pc游戏code扩展');
            $table->string('mobile_game_code',64)->nullable()->comment('手机游戏code');
            $table->string('mobile_game_deputy_code',64)->nullable()->comment('手机游戏code扩展');
            $table->string('record_match_code',128)->nullable()->comment('通过此code获取记录（暂无平台使用)');
            $table->string('record_match_deputy_code',128)->nullable()->comment('通过此code获取记录扩展（暂无平台使用）');
            $table->string('img',256)->nullable()->comment('图片地址');
            $table->string('type',32)->nullable()->comment('游戏类型（暂无平台使用）');
            $table->string('category',32)->nullable()->comment('游戏类型');
            $table->integer('line_num')->default(0)->comment('线路');
            $table->integer('able_demo')->default(0)->comment('是否支持试玩 1支持 0不支持');
            $table->integer('able_recommend')->default(0)->comment('游戏推荐 1推荐 0不推荐');
            $table->decimal('bonus_pool',13,3)->default(0.000)->comment('奖金池');
            $table->tinyInteger('home')->default(0)->comment('首页推荐 1推荐 0不推荐');
            $table->integer('add_admin_id')->default(999999)->comment('操作者');
            $table->tinyInteger('status')->default(1)->comment('状态 1显示 0隐藏');
            $table->timestamps();
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
