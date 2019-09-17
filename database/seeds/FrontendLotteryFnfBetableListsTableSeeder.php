<?php

use Illuminate\Database\Seeder;

class FrontendLotteryFnfBetableListsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('frontend_lottery_fnf_betable_lists')->delete();
        
        \DB::table('frontend_lottery_fnf_betable_lists')->insert(array (
            0 => 
            array (
                'id' => 3,
                'lotteries_id' => 'cqssc',
                'method_id' => 94,
                'sort' => 2,
                'created_at' => '2019-06-04 15:01:43',
                'updated_at' => '2019-08-15 15:51:01',
            ),
            1 => 
            array (
                'id' => 4,
                'lotteries_id' => 'zx1fc',
                'method_id' => 411,
                'sort' => 1,
                'created_at' => '2019-06-04 15:02:27',
                'updated_at' => '2019-08-15 15:51:01',
            ),
            2 => 
            array (
                'id' => 5,
                'lotteries_id' => 'txffc',
                'method_id' => 519,
                'sort' => 3,
                'created_at' => '2019-06-04 15:48:28',
                'updated_at' => '2019-08-13 16:09:04',
            ),
            3 => 
            array (
                'id' => 6,
                'lotteries_id' => 'hljssc',
                'method_id' => 308,
                'sort' => 4,
                'created_at' => '2019-07-13 17:03:09',
                'updated_at' => '2019-08-02 15:45:04',
            ),
        ));
        
        
    }
}