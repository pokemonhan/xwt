<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateForeignKeyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('backend_admin_access_groups', function (Blueprint $table) {
            $table->foreign('platform_id','fk_partner_access_platform_id')
                ->references('platform_id')
                ->on('system_platforms')
                ->onDelete('CASCADE')
                ->onUpdate('CASCADE');
        });

        Schema::table('backend_admin_routes', function (Blueprint $table) {
            $table->foreign('menu_group_id','fk_partner_admin_route_menu_group')
                ->references('id')
                ->on('backend_system_menus')
                ->onDelete('NO ACTION')
                ->onUpdate('CASCADE');
        });

        Schema::table('backend_admin_users', function (Blueprint $table) {
            $table->foreign('group_id','backend_admin_users_group_id_fk')
                ->references('id',)
                ->on('backend_admin_access_groups')
                ->onDelete('NO ACTION')
                ->onUpdate('CASCADE');
            $table->foreign(['platform_id', 'status'],'backend_admin_users_status_foreign')
                ->references(['platform_id', 'status'])
                ->on('system_platforms')
                ->onDelete('CASCADE')
                ->onUpdate('CASCADE');
            $table->foreign('super_id','backend_admin_users_super_id_foreign')
                ->references('id')
                ->on('backend_admin_users')
                ->onDelete('CASCADE')
                ->onUpdate('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('backend_admin_access_groups', function(Blueprint $table)
        {
            $table->dropForeign('fk_partner_access_platform_id');
        });
        Schema::table('backend_admin_routes', function(Blueprint $table)
        {
            $table->dropForeign('fk_partner_admin_route_menu_group');
        });
        Schema::table('backend_admin_users', function(Blueprint $table)
        {
            $table->dropForeign('backend_admin_users_group_id_fk');
            $table->dropForeign('backend_admin_users_status_foreign');
            $table->dropForeign('backend_admin_users_super_id_foreign');
        });
    }
}
