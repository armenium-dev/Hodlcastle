<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTrainingExportView extends Migration{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up(){
		$_sql = file_get_contents(database_path('/sql/view_training_export.sql'));
		DB::statement('DROP VIEW IF EXISTS training_export');
		DB::statement($_sql);
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down(){
		DB::statement('DROP VIEW IF EXISTS training_export');
	}
}
