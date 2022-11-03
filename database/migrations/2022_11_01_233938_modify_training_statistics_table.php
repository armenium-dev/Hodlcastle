<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyTrainingStatisticsTable extends Migration{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up(){
		Schema::table('training_statistics', function(Blueprint $table){
			$table->dateTime('notify_training')->nullable()->after('start_training');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down(){
		Schema::table('training_statistics', function(Blueprint $table){
			$table->dropColumn('notify_training');
		});
	}
}
