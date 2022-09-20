<?php

use Illuminate\Database\Seeder;

class LanguagesTableSeeder extends Seeder{
	/**
	 * Run the database seeds.
	 * @return void
	 */
	public function run(){
		\DB::table('languages')->delete();

		\DB::table('languages')->insert(array(
			0 => array(
				'id'         => 1,
				'code'       => 'en',
				'name'       => 'English',
				'created_at' => '2019-12-14 10:00:00',
				'updated_at' => '2019-12-14 10:00:00',
			),
			1 => array(
				'id'         => 2,
				'code'       => 'de',
				'name'       => 'German',
				'created_at' => '2019-12-14 10:00:00',
				'updated_at' => '2019-12-14 10:00:00',
			),
			2 => array(
				'id'         => 3,
				'code'       => 'nl',
				'name'       => 'Dutch',
				'created_at' => '2020-03-31 17:10:00',
				'updated_at' => '2020-03-31 17:10:00',
			),
			3 => array(
				'id'         => 4,
				'code'       => 'fr',
				'name'       => 'French',
				'created_at' => '2020-03-31 17:10:00',
				'updated_at' => '2020-03-31 17:10:00',
			),
			4 => array(
				'id'         => 5,
				'code'       => 'pt',
				'name'       => 'Portuguese',
				'created_at' => '2020-03-31 17:10:00',
				'updated_at' => '2020-03-31 17:10:00',
			),
			5 => array(
				'id'         => 6,
				'code'       => 'es',
				'name'       => 'Spanish',
				'created_at' => '2020-03-31 17:10:00',
				'updated_at' => '2020-03-31 17:10:00',
			),
			6 => array(
				'id'         => 7,
				'code'       => 'it',
				'name'       => 'Italian',
				'created_at' => '2020-03-31 17:10:00',
				'updated_at' => '2020-03-31 17:10:00',
			),
			7 => array(
				'id'         => 8,
				'code'       => 'tr',
				'name'       => 'Turkish',
				'created_at' => '2020-03-31 17:10:00',
				'updated_at' => '2020-03-31 17:10:00',
			),
			8 => array(
				'id'         => 9,
				'code'       => 'uk',
				'name'       => 'Ukrainian',
				'created_at' => '2020-03-31 17:10:00',
				'updated_at' => '2020-03-31 17:10:00',
			),
			9 => array(
				'id'         => 10,
				'code'       => 'sa',
				'name'       => 'Arabic',
				'created_at' => '2020-03-31 17:10:00',
				'updated_at' => '2020-03-31 17:10:00',
			),
			10 => array(
				'id'         => 11,
				'code'       => 'ru',
				'name'       => 'Russian',
				'created_at' => '2020-03-31 17:10:00',
				'updated_at' => '2020-03-31 17:10:00',
			),
		));
	}
}
