<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToLandingTemplatesTable extends Migration{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up(){
		Schema::table('landing_templates', function(Blueprint $table){
			$table->json('options')->after('url');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down(){
		Schema::table('landing_templates', function(Blueprint $table){
			$table->dropColumn('options');
		});
	}
}
