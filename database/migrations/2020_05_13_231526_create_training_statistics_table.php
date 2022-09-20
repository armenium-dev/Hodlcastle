<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTrainingStatisticsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('training_statistics', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('recipient_id');
            $table->integer('company_id');
            $table->string('code');
            $table->dateTime('start_training')->nullable();
            $table->dateTime('finish_training')->nullable();
            $table->integer('is_finish')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('training_statistics');
    }
}
