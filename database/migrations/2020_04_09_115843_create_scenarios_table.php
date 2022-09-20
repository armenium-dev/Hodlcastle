<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateScenariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('scenarios', function (Blueprint $table) {
            $table->increments('id');
	        $table->string('name', 255)->nullable();
	        $table->text('description')->nullable();
	        $table->tinyInteger('language_id')->default(1);
	        $table->tinyInteger('is_active')->default(1);
	        $table->string('campaign_name', 255)->nullable();
	        $table->integer('email_template_id')->default(0);
	        $table->integer('domain_id')->default(0);
	        $table->string('email', 255)->nullable();
	        $table->tinyInteger('is_short')->default(0);
	        $table->tinyInteger('send_to_landing')->default(1);
	        $table->string('redirect_url', 255)->nullable();
	        $table->tinyInteger('with_attach')->default(0);
	        $table->integer('created_by_user_id')->default(0);
	        $table->integer('updated_by_user_id')->default(0);
	        $table->integer('deleted_by_user_id')->default(0);
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
        Schema::dropIfExists('scenarios');
    }
}
