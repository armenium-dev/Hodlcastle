<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToSentSmsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sent_sms', function (Blueprint $table) {
	        $table->string('phone', 15)->nullable()->after('recipient');
	        $table->integer('campaign_id')->default(0)->after('phone');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sent_sms', function (Blueprint $table) {
	        $table->dropColumn(['phone', 'campaign_id']);
        });
    }
}
