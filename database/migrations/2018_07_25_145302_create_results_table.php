<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateResultsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('results', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('campaign_id')->unsigned();
            $table->integer('customer_id')->unsigned();
            $table->integer('redirect_id')->unsigned();
            $table->string('email');
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->integer('status');
            $table->string('ip');
            $table->string('lat');
            $table->string('lng');
            $table->timestamp('send_date');
            $table->integer('reported');
            $table->integer('sent');
            $table->integer('open');
            $table->integer('click');
            $table->integer('recipient_id')->unsigned();
            $table->integer('event_id')->unsigned();
            $table->integer('type_id')->unsigned();
            $table->string('user_agent')->nullable();
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
        Schema::drop('results');
    }
}
