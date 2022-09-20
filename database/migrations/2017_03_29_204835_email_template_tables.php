<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EmailTemplateTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('email_layouts', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->text('layout');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('email_templates', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->integer('company_id')->unsigned()->nullable();
            $table->integer('layout_id')->unsigned()->nullable();
            $table->integer('is_public')->default(0);
            $table->string('handle', 128)->nullable();
            $table->string('name', 128);
            $table->string('subject', 128);
            $table->text('text')->nullable();
            $table->text('html')->nullable();
            $table->integer('text_type')->default(0);
            $table->string('lang', 4)->default('en');
            $table->string('tags');
            $table->timestamps();
            $table->softDeletes();

            //$table->foreign('layout_id')->references('id')->on('email_layout');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('email_templates');
        Schema::dropIfExists('email_layouts');
    }
}
