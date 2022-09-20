<?php

use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('permissions')->delete();
        
        \DB::table('permissions')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'viewCompany',
                'guard_name' => 'web',
                'created_at' => '2018-09-12 10:17:03',
                'updated_at' => '2018-09-12 10:17:03',
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'addCompany',
                'guard_name' => 'web',
                'created_at' => '2018-09-12 10:17:03',
                'updated_at' => '2018-09-12 10:17:03',
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'editCompany',
                'guard_name' => 'web',
                'created_at' => '2018-09-12 10:17:03',
                'updated_at' => '2018-09-12 10:17:03',
            ),
            3 => 
            array (
                'id' => 4,
                'name' => 'deleteCompany',
                'guard_name' => 'web',
                'created_at' => '2018-09-12 10:17:03',
                'updated_at' => '2018-09-12 10:17:03',
            ),
            4 => 
            array (
                'id' => 5,
                'name' => 'groups.index',
                'guard_name' => 'web',
                'created_at' => '2018-10-16 12:10:50',
                'updated_at' => '2018-10-16 12:10:50',
            ),
            5 => 
            array (
                'id' => 6,
                'name' => 'emailTemplates.index',
                'guard_name' => 'web',
                'created_at' => '2018-10-16 12:11:16',
                'updated_at' => '2018-10-16 12:11:16',
            ),
            6 => 
            array (
                'id' => 7,
                'name' => 'domains.index',
                'guard_name' => 'web',
                'created_at' => '2018-10-16 12:46:06',
                'updated_at' => '2018-10-16 12:46:06',
            ),
            7 => 
            array (
                'id' => 8,
                'name' => 'landings.index',
                'guard_name' => 'web',
                'created_at' => '2018-10-16 12:46:19',
                'updated_at' => '2018-10-16 12:46:19',
            ),
            8 => 
            array (
                'id' => 9,
                'name' => 'campaigns.index',
                'guard_name' => 'web',
                'created_at' => '2018-10-16 12:46:29',
                'updated_at' => '2018-10-16 12:46:29',
            ),
            9 => 
            array (
                'id' => 10,
                'name' => 'documentation.index',
                'guard_name' => 'web',
                'created_at' => '2018-10-16 12:46:38',
                'updated_at' => '2018-10-16 12:46:38',
            ),
            10 => 
            array (
                'id' => 11,
                'name' => 'companies.index',
                'guard_name' => 'web',
                'created_at' => '2018-10-16 12:46:47',
                'updated_at' => '2018-10-16 12:46:47',
            ),
            11 => 
            array (
                'id' => 12,
                'name' => 'users.index',
                'guard_name' => 'web',
                'created_at' => '2018-10-16 12:46:56',
                'updated_at' => '2018-10-16 12:46:56',
            ),
            12 => 
            array (
                'id' => 13,
                'name' => 'roles.index',
                'guard_name' => 'web',
                'created_at' => '2018-10-16 12:47:03',
                'updated_at' => '2018-10-16 12:47:03',
            ),
            13 => 
            array (
                'id' => 14,
                'name' => 'permissions.index',
                'guard_name' => 'web',
                'created_at' => '2018-10-16 12:47:11',
                'updated_at' => '2018-10-16 12:47:11',
            ),
            14 => 
            array (
                'id' => 15,
                'name' => 'mailTracker_Index',
                'guard_name' => 'web',
                'created_at' => '2018-10-16 12:47:18',
                'updated_at' => '2018-10-16 12:47:18',
            ),
            15 => 
            array (
                'id' => 16,
                'name' => 'events.index',
                'guard_name' => 'web',
                'created_at' => '2018-10-16 12:47:26',
                'updated_at' => '2018-10-16 12:47:26',
            ),
            16 => 
            array (
                'id' => 17,
                'name' => 'results.index',
                'guard_name' => 'web',
                'created_at' => '2018-10-16 12:47:33',
                'updated_at' => '2018-10-16 12:47:33',
            ),
            17 => 
            array (
                'id' => 18,
                'name' => 'supergroups.index',
                'guard_name' => 'web',
                'created_at' => '2018-10-16 12:47:40',
                'updated_at' => '2018-10-16 12:47:40',
            ),
            18 => 
            array (
                'id' => 19,
                'name' => 'schedules.landing_select',
                'guard_name' => 'web',
                'created_at' => '2018-11-23 15:00:51',
                'updated_at' => '2018-11-23 15:00:51',
            ),
            19 => 
            array (
                'id' => 20,
                'name' => 'domains.set_public',
                'guard_name' => 'web',
                'created_at' => '2018-12-10 08:52:11',
                'updated_at' => '2018-12-10 08:52:11',
            ),
            20 => 
            array (
                'id' => 21,
                'name' => 'domain.view_company',
                'guard_name' => 'web',
                'created_at' => '2018-12-10 09:03:05',
                'updated_at' => '2018-12-10 09:03:05',
            ),
            21 => 
            array (
                'id' => 22,
                'name' => 'company.set_trial',
                'guard_name' => 'web',
                'created_at' => '2018-12-10 09:55:30',
                'updated_at' => '2018-12-10 09:55:30',
            ),
            22 => 
            array (
                'id' => 23,
                'name' => 'email_template.set_public',
                'guard_name' => 'web',
                'created_at' => '2018-12-10 10:46:04',
                'updated_at' => '2018-12-10 10:46:04',
            ),
            23 => 
            array (
                'id' => 24,
                'name' => 'email_template.edit_public',
                'guard_name' => 'web',
                'created_at' => '2018-12-22 09:02:34',
                'updated_at' => '2018-12-22 09:02:45',
            ),
            24 => 
            array (
                'id' => 25,
                'name' => 'email_template.set_company',
                'guard_name' => 'web',
                'created_at' => '2018-12-22 09:06:29',
                'updated_at' => '2018-12-22 09:06:29',
            ),
            25 => 
            array (
                'id' => 26,
                'name' => 'email_template.see_lang_tags_image',
                'guard_name' => 'web',
                'created_at' => '2019-01-17 00:05:40',
                'updated_at' => '2019-01-17 00:05:40',
            ),
            26 => 
            array (
                'id' => 27,
                'name' => 'company.viewAll',
                'guard_name' => 'web',
                'created_at' => '2019-01-18 16:41:25',
                'updated_at' => '2019-01-18 16:41:25',
            ),
            27 => 
            array (
                'id' => 28,
                'name' => 'email_template.edit_not_public_models',
                'guard_name' => 'web',
                'created_at' => '2019-01-19 10:24:36',
                'updated_at' => '2019-01-19 10:24:36',
            ),
            28 => 
            array (
                'id' => 29,
                'name' => 'email_template.view_not_public_models',
                'guard_name' => 'web',
                'created_at' => '2019-01-19 10:24:49',
                'updated_at' => '2019-01-19 10:24:49',
            ),
            29 =>
                array (
                    'id' => 30,
                    'name' => 'domain.edit_public',
                    'guard_name' => 'web',
                    'created_at' => '2019-10-14 19:00:00',
                    'updated_at' => '2019-10-14 19:00:00',
                ),
            30 =>
                array (
                    'id' => 31,
                    'name' => 'domain.view_not_public_models',
                    'guard_name' => 'web',
                    'created_at' => '2019-10-14 19:00:00',
                    'updated_at' => '2019-10-14 19:00:00',
                ),
            31 =>
                array (
                    'id' => 32,
                    'name' => 'modules.index',
                    'guard_name' => 'web',
                    'created_at' => '2020-03-20 19:00:00',
                    'updated_at' => '2019-03-20 19:00:00',
                ),
            32 =>
                array (
                    'id' => 33,
                    'name' => 'module.viewAll',
                    'guard_name' => 'web',
                    'created_at' => '2020-03-20 19:05:00',
                    'updated_at' => '2020-03-20 19:05:00',
                ),
            33 =>
                array (
                    'id' => 34,
                    'name' => 'module.add',
                    'guard_name' => 'web',
                    'created_at' => '2020-03-20 19:06:00',
                    'updated_at' => '2020-03-20 19:06:00',
                ),
            34 =>
                array (
                    'id' => 35,
                    'name' => 'module.edit',
                    'guard_name' => 'web',
                    'created_at' => '2020-03-20 19:06:00',
                    'updated_at' => '2020-03-20 19:06:00',
                ),
            35 =>
                array (
                    'id' => 36,
                    'name' => 'module.delete',
                    'guard_name' => 'web',
                    'created_at' => '2020-03-20 19:06:00',
                    'updated_at' => '2020-03-20 19:06:00',
                ),
            36 =>
                array (
                    'id' => 37,
                    'name' => 'courses.index',
                    'guard_name' => 'web',
                    'created_at' => '2020-03-20 19:00:00',
                    'updated_at' => '2019-03-20 19:00:00',
                ),
            37 =>
                array (
                    'id' => 38,
                    'name' => 'course.viewAll',
                    'guard_name' => 'web',
                    'created_at' => '2020-03-20 19:05:00',
                    'updated_at' => '2020-03-20 19:05:00',
                ),
            38 =>
                array (
                    'id' => 39,
                    'name' => 'course.add',
                    'guard_name' => 'web',
                    'created_at' => '2020-03-20 19:06:00',
                    'updated_at' => '2020-03-20 19:06:00',
                ),
            39 =>
                array (
                    'id' => 40,
                    'name' => 'course.edit',
                    'guard_name' => 'web',
                    'created_at' => '2020-03-20 19:06:00',
                    'updated_at' => '2020-03-20 19:06:00',
                ),
            40 =>
                array (
                    'id' => 41,
                    'name' => 'course.delete',
                    'guard_name' => 'web',
                    'created_at' => '2020-03-20 19:06:00',
                    'updated_at' => '2020-03-20 19:06:00',
                ),
            41 =>
                array (
                    'id' => 42,
                    'name' => 'trainings.index',
                    'guard_name' => 'web',
                    'created_at' => '2020-03-20 19:06:00',
                    'updated_at' => '2020-03-20 19:06:00',
                ),
            42 =>
                array (
                    'id' => 43,
                    'name' => 'scenario.builder',
                    'guard_name' => 'web',
                    'created_at' => '2020-04-10 18:45:07',
                    'updated_at' => '2020-04-12 19:28:03',
                ),
            43 =>
                array (
                    'id' => 44,
                    'name' => 'scenarios.index',
                    'guard_name' => 'web',
                    'created_at' => '2020-04-14 01:33:21',
                    'updated_at' => '2020-04-14 01:33:21',
                ),
        ));
        
        
    }
}