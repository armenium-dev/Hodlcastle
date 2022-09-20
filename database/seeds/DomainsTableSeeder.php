<?php

use Illuminate\Database\Seeder;

class DomainsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('domains')->delete();
        
        \DB::table('domains')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'UEFA',
                'email' => 'noreply@uefa-league.com',
                'url' => 'uefa-league.com',
                'company_id' => 1,
                'is_public' => 1,
                'created_at' => '2018-09-12 10:17:03',
                'updated_at' => '2019-01-21 12:54:41',
                'deleted_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'URL Shortening Service',
                'email' => 'noreply@url2short.com',
                'url' => '',
                'company_id' => 1,
                'is_public' => 1,
                'created_at' => '2018-09-12 10:17:03',
                'updated_at' => '2019-01-01 18:43:35',
                'deleted_at' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'hodlcastle.com',
                'email' => 'noreply@hodlcastle.com',
                'url' => '',
                'company_id' => 4,
                'is_public' => 0,
                'created_at' => '2018-09-12 10:17:03',
                'updated_at' => '2018-09-12 10:17:03',
                'deleted_at' => NULL,
            ),
            3 => 
            array (
                'id' => 4,
                'name' => 'Cyber domain',
                'email' => 'cccc@cdyne.com',
                'url' => '',
                'company_id' => 2,
                'is_public' => 0,
                'created_at' => '2018-12-10 09:26:23',
                'updated_at' => '2018-12-10 09:26:23',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}