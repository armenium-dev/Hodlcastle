<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroupRecipientTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('group_recipient', function (Blueprint $table) {
            $table->integer('group_id')->unsigned();
            $table->integer('recipient_id')->unsigned();
        });
        Schema::table('recipients', function (Blueprint $table) {
            $table->dropColumn('group_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('group_recipient');
        Schema::table('recipients', function (Blueprint $table) {
            $table->integer('group_id')->unsigned();
        });
    }
}
