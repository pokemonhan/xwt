<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommonSystemLog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('backend_system_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('log_uuid',45)->nullable()->comment('唯一标识id');
            $table->text('description')->nullable()->comment('描述');
            $table->string('origin',200)->nullable()->comment('域名');
            $table->enum('type',['log','store','change','delete'])->comment('类型');
            $table->enum('result',['success','neutral','failure'])->comment('结果');
            $table->enum('level',['emergency','alert','critical','error','warning','notice','info','debug'])->comment('等级');
            $table->string('token',100)->nullable()->comment('token');
            $table->string('ip',45);
            $table->string('ips',200)->nullable();
            $table->integer('user_id')->nullable();
            $table->string('session',100)->nullable();
            $table->string('lang',50)->nullable();
            $table->string('device',20)->nullable()->comment('设备');
            $table->string('os',20)->nullable()->comment('系统');
            $table->string('os_version',50)->nullable()->comment('系统版本');
            $table->string('browser',50)->nullable()->comment('浏览器');
            $table->string('bs_version',50)->nullable();
            $table->tinyInteger('device_type')->nullable();
            $table->string('robot',50)->nullable();
            $table->string('user_agent',200)->nullable();
            $table->text('inputs')->nullable()->comment('传递参数');
            $table->text('route')->nullable()->comment('路由');
            $table->unsignedInteger('route_id')->nullable()->comment('路由id （backend_admin_routes表id）');
            $table->integer('admin_id')->nullable();
            $table->string('admin_name',64)->nullable();
            $table->string('username',64)->nullable();
            $table->integer('menu_id')->nullable();
            $table->string('menu_label',64)->nullable();
            $table->text('menu_path')->nullable();
            $table->nullableTimestamps();
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });

        Schema::create('frontend_system_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('log_uuid',45)->nullable()->comment('唯一标识id');
            $table->text('description')->nullable()->comment('描述');
            $table->string('origin',200)->nullable()->comment('域名');
            $table->enum('type',['log','store','change','delete'])->comment('类型');
            $table->enum('result',['success','neutral','failure'])->comment('结果');
            $table->enum('level',['emergency','alert','critical','error','warning','notice','info','debug'])->comment('等级');
            $table->string('token',100)->nullable()->comment('token');
            $table->string('ip',45);
            $table->string('ips',200)->nullable();
            $table->integer('user_id')->nullable();
            $table->string('session',100)->nullable();
            $table->string('lang',50)->nullable();
            $table->string('device',20)->nullable()->comment('设备');
            $table->string('os',20)->nullable()->comment('系统');
            $table->string('os_version',50)->nullable()->comment('系统版本');
            $table->string('browser',50)->nullable()->comment('浏览器');
            $table->string('bs_version',50)->nullable();
            $table->tinyInteger('device_type')->nullable();
            $table->string('robot',50)->nullable();
            $table->string('user_agent',200)->nullable();
            $table->text('inputs')->nullable()->comment('传递参数');
            $table->text('route')->nullable()->comment('路由');
            $table->unsignedInteger('route_id')->nullable()->comment('路由id （backend_admin_routes表id）');
            $table->integer('admin_id')->nullable();
            $table->string('admin_name',64)->nullable();
            $table->string('username',64)->nullable();
            $table->integer('menu_id')->nullable();
            $table->string('menu_label',64)->nullable();
            $table->text('menu_path')->nullable();
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
        Schema::dropIfExists('backend_system_logs');
        Schema::dropIfExists('frontend_system_logs');
    }
}
