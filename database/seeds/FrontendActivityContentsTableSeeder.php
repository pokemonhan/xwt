<?php

use Illuminate\Database\Seeder;

class FrontendActivityContentsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('frontend_activity_contents')->delete();
        
        \DB::table('frontend_activity_contents')->insert(array (
            0 => 
            array (
                'id' => 15,
                'title' => '11',
                'content' => '11',
                'pic_path' => '/uploaded_files/aa_1/mobile_activity_aa_1/9769c860805c026c759ca87c98c0547a.png',
                'preview_pic_path' => '/uploaded_files/aa_1/mobile_activity_aa_1/af8d63364a6522f47191e11e00a46edb.jpg',
                'start_time' => NULL,
                'end_time' => NULL,
                'status' => 0,
                'admin_id' => 4,
                'admin_name' => 'york',
                'is_redirect' => 0,
                'redirect_url' => 'undefined',
                'is_time_interval' => 0,
                'sort' => 1,
                'created_at' => '2019-08-06 17:03:20',
                'updated_at' => '2019-09-03 11:47:16',
                'type' => 2,
            ),
        ));
        
        
    }
}