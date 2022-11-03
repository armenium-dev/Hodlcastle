<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToTrainingsTable extends Migration{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up(){
		Schema::table('trainings', function(Blueprint $table){
			$table->integer('notify_template_id')->default(0)->after('user_id');
			$table->integer('finish_template_id')->default(0)->after('user_id');
			$table->integer('start_template_id')->default(0)->after('user_id');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down(){
		Schema::table('trainings', function(Blueprint $table){
			$table->dropColumn(['start_template_id', 'finish_template_id', 'notify_template_id']);
		});
	}
}
