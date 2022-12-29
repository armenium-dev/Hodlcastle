<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ReplaceFieldsInCompaniesTable extends Migration{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up(){
		Schema::table('companies', function(Blueprint $table){
			$table->integer('profile_id')->default(0)->after('smishing');
			$table->dropColumn('smishing');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down(){
		Schema::table('companies', function(Blueprint $table){
			$table->integer('smishing')->default(0)->after('profile_id');
			$table->dropColumn('profile_id');
		});
	}
}
