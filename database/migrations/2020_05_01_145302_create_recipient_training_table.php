<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateRecipientTrainingTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recipient_training', function (Blueprint $table) {
            $table->integer('training_id')->unsigned();
            $table->integer('recipient_id')->unsigned();
            $table->string('code');
            $table->integer('is_sent')->default(0);
        });
    }

    public function down()
    {
        Schema::drop('recipient_training');
    }
}