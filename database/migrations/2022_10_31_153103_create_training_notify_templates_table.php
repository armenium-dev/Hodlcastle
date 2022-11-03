<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTrainingNotifyTemplatesTable extends Migration{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up(){
		Schema::create('training_notify_templates', function(Blueprint $table){
			$table->increments('id')->unsigned();
			$table->integer('company_id')->unsigned()->nullable();
			$table->integer('module_id')->unsigned()->nullable();
			$table->integer('type_id')->default(0);
			$table->integer('language_id')->default(1);
			$table->integer('is_public')->default(0);
			$table->string('name', 255)->nullable();
			$table->string('subject', 255)->nullable();
			$table->text('content')->nullable();
			$table->timestamps();
			$table->softDeletes();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down(){
		Schema::dropIfExists('training_notify_templates');
	}
}
