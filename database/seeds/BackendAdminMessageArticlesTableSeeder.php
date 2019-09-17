<?php

use Illuminate\Database\Seeder;

class BackendAdminMessageArticlesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('backend_admin_message_articles')->delete();
        
        \DB::table('backend_admin_message_articles')->insert(array (
            0 => 
            array (
                'id' => 11,
                'category_id' => 2,
                'title' => 'Ling测试',
                'summary' => 'Ling测试',
                'content' => '<p>Ling测试</p>',
                'search_text' => 'Ling测试',
                'is_for_agent' => 1,
                'status' => 0,
                'audit_flow_id' => 282,
                'add_admin_id' => 4,
                'last_update_admin_id' => 4,
                'sort' => 1,
                'created_at' => '2019-08-13 21:07:06',
                'updated_at' => '2019-08-13 21:07:06',
                'pic_path' => NULL,
            ),
        ));
        
        
    }
}