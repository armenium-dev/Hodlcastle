<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToTrainingStatisticsTable extends Migration{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up(){
		Schema::table('training_statistics', function(Blueprint $table){
			$table->json('answers_data')->nullable()->after('is_finish');
			$table->integer('user_score')->default(0)->after('is_finish');
			$table->integer('wrong_answers')->default(0)->after('is_finish');
			$table->integer('correct_answers')->default(0)->after('is_finish');
			$table->integer('total_answers')->default(0)->after('is_finish');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down(){
		Schema::table('training_statistics', function(Blueprint $table){
			$table->dropColumn(['user_score', 'wrong_answers', 'correct_answers', 'total_answers', 'answers_data']);
		});
	}
}
