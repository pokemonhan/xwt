<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommonDomain extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('backend_domains', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->nullable()->comment('用户ID 属于哪个用户');
            $table->string('domain',255)->comment('域名');
            $table->integer('config_id')->default(0)->comment('配置文件');
            $table->tinyInteger('is_use')->default(1)->comment('是否启用,默认启用');
            $table->index('domain','domain');
            $table->nullableTimestamps();
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_general_ci';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('backend_domains');
    }
}
