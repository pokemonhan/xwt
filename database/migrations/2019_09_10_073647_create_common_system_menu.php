<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommonSystemMenu extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('backend_system_menus', function (Blueprint $table) {
            $table->increments('id');
            $table->string('label',20)->nullable()->collation('utf8_general_ci')->comment('名称');
            $table->string('en_name',50)->nullable()->collation('utf8_general_ci')->comment('英文名');
            $table->string('route',50)->nullable()->collation('utf8_general_ci')->default('#')->comment('路由');
            $table->integer('pid')->default(0)->nullable()->comment('菜单的父级别');
            $table->string('icon',50)->nullable()->collation('utf8_general_ci')->comment('图标');
            $table->tinyInteger('display')->default(1)->nullable()->comment('是否显示');
            $table->integer('level')->default(1)->nullable()->comment('等级');
            $table->integer('sort')->nullable()->comment('排序');
            $table->nullableTimestamps();
            $table->engine = 'InnoDB';
            $table->charset = 'utf8';
            $table->collation = 'utf8_unicode_ci';
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
