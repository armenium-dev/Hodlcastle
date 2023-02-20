<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCompanyIdInAccountActivitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('account_activities', function (Blueprint $table) {
            $table->integer('company_id')->unsigned()->nullable()->after('user_id');
            $table->integer('campaign_id')->unsigned()->nullable()->after('company_id');
            $table->integer('sms_credit')->nullable()->after('ip_address');

            $table->foreign('company_id')
                ->references('id')
                ->on('companies');

            $table->foreign('campaign_id')
                ->references('id')
                ->on('campaigns');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('account_activities', function (Blueprint $table) {
            $table->dropColumn('company_id');
            $table->dropColumn('sms_credit');
        });
    }
}
