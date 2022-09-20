<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSentAttachEmailsUrlOpenesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sent_attach_emails_url_openes', function (Blueprint $table) {
	        $table->increments('id');
	        $table->integer('sent_attach_email_id', 0);
	        $table->string('hash', 32);
	        $table->string('url', 255);
	        $table->integer('opens')->default(0);
	        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sent_attach_emails_url_openes');
    }
}
