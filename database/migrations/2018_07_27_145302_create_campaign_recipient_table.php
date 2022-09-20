<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCampaignRecipientTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('campaign_recipient', function (Blueprint $table) {
            $table->integer('campaign_id')->unsigned();
            $table->integer('recipient_id')->unsigned();
            $table->string('code');
            $table->integer('is_sent')->default(0);
        });
    }

    public function down()
    {
        Schema::drop('campaign_recipient');
    }
}