<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

// 娱乐城
class CreateGameCasinoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 游戏平台
        Schema::create('casino_platforms', function (Blueprint $table) {
            $table->increments('id');

            $table->string('main_game_plat_name',64);
            $table->string('main_game_plat_code',64);

            $table->decimal('rate', 5,4)->nullable();

            $table->integer('status')->default(1);
            $table->integer('add_admin_id')->default(0);
            $table->timestamps();
        });

        // 游戏分类
        Schema::create('casino_categories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name',64);
            $table->string('code',64);
            $table->tinyInteger('home')->default(0);

            $table->integer('add_admin_id')->default(0);
            $table->tinyInteger('status')->default(1);

            $table->timestamps();
        });

        // 游戏列表
        Schema::create('casino_game_lists', function (Blueprint $table) {
            $table->increments('id');
            $table->string('main_game_plat_code',64);
            $table->string('cn_name',128);
            $table->string('en_name',128);
            $table->string('pc_game_code',64)->nullable();
            $table->string('pc_game_deputy_code',64)->nullable();
            $table->string('mobile_game_code',64)->nullable();
            $table->string('mobile_game_deputy_code',64)->nullable();
            $table->string('record_match_code',128)->nullable();
            $table->string('record_match_deputy_code',128)->nullable();
            $table->string('img',256)->nullable();
            $table->string('type',32)->nullable();
            $table->string('category',32)->nullable();
            $table->integer('line_num')->default(0);
            $table->integer('able_demo')->default(0);
            $table->integer('able_recommend')->default(0);
            $table->decimal('bonus_pool',13,3)->default(0.000);

            $table->tinyInteger('home')->default(0);

            $table->integer('add_admin_id')->default(0);
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
        });

        // 游戏接口记录
        Schema::create('casino_api_log', function (Blueprint $table) {
            $table->increments('id');
            $table->string('api',64);
            $table->integer('user_id');
            $table->string('from',64);
            $table->string('username',64);
            $table->string('recreation_name',64);
            $table->string('ip',64);
            $table->text('params');
            $table->text('return_content');

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
        Schema::dropIfExists('casino_platforms');
        Schema::dropIfExists('casino_categories');
        Schema::dropIfExists('casino_game_lists');
        Schema::dropIfExists('casino_api_log');
    }
}
