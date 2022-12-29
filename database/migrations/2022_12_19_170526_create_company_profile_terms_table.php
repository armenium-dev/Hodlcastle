<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompanyProfileTermsTable extends Migration{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up(){
		Schema::create('company_profile_terms', function(Blueprint $table){
			$table->bigIncrements('id');
			$table->string('name')->nullabe();
			$table->string('slug')->nullabe();
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down(){
		Schema::dropIfExists('company_profile_terms');
	}
}
