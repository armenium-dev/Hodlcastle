<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSmishingFieldToCompaniesTable extends Migration{
	/**
	 * Run the migrations.
	 * @return void
	 */
	public function up(){
		Schema::table('companies', function(Blueprint $table){
			$table->integer('smishing')->default(0);
		});
	}
	
	/**
	 * Reverse the migrations.
	 * @return void
	 */
	public function down(){
		Schema::table('companies', function(Blueprint $table){
			$table->dropColumn(['smishing']);
		});
	}
}
