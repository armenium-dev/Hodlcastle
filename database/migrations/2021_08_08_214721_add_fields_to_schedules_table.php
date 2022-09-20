<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToSchedulesTable extends Migration{
	/**
	 * Run the migrations.
	 * @return void
	 */
	public function up(){
		Schema::table('schedules', function(Blueprint $table){
			$table->integer('sms_template_id')->default(0)->after('email_template_id');
		});
	}
	
	/**
	 * Reverse the migrations.
	 * @return void
	 */
	public function down(){
		Schema::table('schedules', function(Blueprint $table){
			$table->dropColumn(['sms_template_id']);
		});
	}
}
