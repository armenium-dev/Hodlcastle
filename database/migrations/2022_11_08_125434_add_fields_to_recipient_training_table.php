<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToRecipientTrainingTable extends Migration{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up(){
		Schema::table('recipient_training', function(Blueprint $table){
			$table->integer('phase')->default(1);
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down(){
		Schema::table('recipient_training', function(Blueprint $table){
			$table->dropColumn('phase');
			$table->dropTimestamps();
		});
	}
}
