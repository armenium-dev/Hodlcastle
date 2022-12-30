<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToSettingsTable extends Migration{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up(){
		Schema::table('settings', function(Blueprint $table){
			$table->string('option_name', 50)->nullable()->after('id');
			$table->integer('custom_option')->default(0);
			$table->string('custom_option_page_link', 255)->nullable();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down(){
		Schema::table('settings', function(Blueprint $table){
			$table->dropColumn(['custom_option', 'custom_option_page_link']);
		});
	}
}
