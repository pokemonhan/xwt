<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFrontendModule extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('frontend_allocated_models', function (Blueprint $table) {
            $table->increments('id');
            $table->string('label',20)->nullable()->comment('名称');
            $table->string('en_name',45)->nullable()->comment('英文名');
            $table->integer('pid')->nullable()->comment('父级id');
            $table->tinyInteger('type')->nullable()->comment('1通用 2web 3app');
            $table->string('value',128)->nullable();
            $table->tinyInteger('show_num')->nullable()->comment('展示数量');
            $table->tinyInteger('status')->nullable()->comment('状态 0关闭 1开启');
            $table->tinyInteger('level')->nullable()->comment('等级');
            $table->tinyInteger('is_homepage_display')->nullable()->comment('是否是首页显示');
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
        Schema::dropIfExists('frontend_allocated_models');
    }
}
