<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdatePageQuizQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('page_quiz_questions', function (Blueprint $table) {
            $table->renameColumn('question', 'answer');
            $table->tinyInteger('correct')->after('page_quiz_id')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('page_quiz_questions', function (Blueprint $table) {
            $table->renameColumn('answer', 'question');
            $table->dropColumn('correct');
        });
    }
}
