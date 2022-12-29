<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompanyProfileRulesTable extends Migration{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up(){
		Schema::create('company_profile_rules', function(Blueprint $table){
			$table->bigIncrements('id');
			$table->unsignedBigInteger('profile_id');
			$table->unsignedBigInteger('term_id');
			$table->integer('active')->default(0);

			$table->foreign('profile_id')->references('id')->on('company_profiles');
			$table->foreign('term_id')->references('id')->on('company_profile_terms');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down(){
		Schema::dropIfExists('company_profile_rules');
	}
}
