<?php

use Illuminate\Database\Seeder;

class FrontendUsersTransferedRecordsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('frontend_users_transfered_records')->delete();
        
        
        
    }
}