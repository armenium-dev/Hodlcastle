<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSmsTemplateTable extends Migration{
	/**
	 * Run the migrations.
	 * @return void
	 */
	public function up(){
		Schema::create('sms_templates', function(Blueprint $table){
			$table->increments('id');
			$table->integer('company_id')->unsigned()->nullable();
			$table->integer('language_id')->unsigned()->default(1);
			$table->string('name', 255)->nullable();
			$table->text('content')->nullable();
			$table->integer('is_public')->default(0);
			$table->timestamps();
			$table->softDeletes();
		});
	}
	
	/**
	 * Reverse the migrations.
	 * @return void
	 */
	public function down(){
		Schema::dropIfExists('sms_templates');
	}
}
