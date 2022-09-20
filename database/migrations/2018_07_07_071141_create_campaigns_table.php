<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCampaignsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('campaigns', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('scheduled_type')->default(0);
            $table->integer('schedule_id')->unsigned()->nullable();
            $table->integer('supergroup_id')->unsigned()->nullable();
            $table->integer('company_id')->unsigned();
            $table->integer('status')->default(0);
            $table->datetime('completed_at')->nullable();
            $table->integer('is_scheduled')->default(0);
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
        Schema::drop('campaigns');
    }
}
