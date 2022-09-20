<?php

use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('roles')->delete();
        
        \DB::table('roles')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'captain',
                'guard_name' => 'web',
                'created_at' => '2018-09-12 10:17:03',
                'updated_at' => '2018-09-12 10:17:03',
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'customer',
                'guard_name' => 'web',
                'created_at' => '2018-09-12 10:17:04',
                'updated_at' => '2018-09-12 10:17:04',
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'maintainer',
                'guard_name' => 'web',
                'created_at' => '2018-10-16 12:00:48',
                'updated_at' => '2018-10-16 12:00:48',
            ),
        ));
        
        
    }
}