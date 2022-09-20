<?php

use Illuminate\Database\Seeder;

class ModelHasRolesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('model_has_roles')->delete();
        
        \DB::table('model_has_roles')->insert(array (
            0 => 
            array (
                'role_id' => 1,
                'model_type' => 'App\\User',
                'model_id' => 1,
            ),
            1 => 
            array (
                'role_id' => 1,
                'model_type' => 'App\\User',
                'model_id' => 2,
            ),
            2 => 
            array (
                'role_id' => 2,
                'model_type' => 'App\\User',
                'model_id' => 3,
            ),
            3 => 
            array (
                'role_id' => 2,
                'model_type' => 'App\\User',
                'model_id' => 4,
            ),
            4 => 
            array (
                'role_id' => 3,
                'model_type' => 'App\\User',
                'model_id' => 15,
            ),
            5 => 
            array (
                'role_id' => 3,
                'model_type' => 'App\\User',
                'model_id' => 16,
            ),
            6 => 
            array (
                'role_id' => 2,
                'model_type' => 'App\\User',
                'model_id' => 18,
            ),
            7 => 
            array (
                'role_id' => 2,
                'model_type' => 'App\\User',
                'model_id' => 19,
            ),
            8 => 
            array (
                'role_id' => 2,
                'model_type' => 'App\\User',
                'model_id' => 20,
            ),
            9 => 
            array (
                'role_id' => 2,
                'model_type' => 'App\\User',
                'model_id' => 21,
            ),
            10 => 
            array (
                'role_id' => 2,
                'model_type' => 'App\\User',
                'model_id' => 25,
            ),
            11 => 
            array (
                'role_id' => 2,
                'model_type' => 'App\\User',
                'model_id' => 26,
            ),
            12 => 
            array (
                'role_id' => 2,
                'model_type' => 'App\\User',
                'model_id' => 27,
            ),
        ));
        
        
    }
}