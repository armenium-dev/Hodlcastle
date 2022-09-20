<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropPageQuizAnswersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('page_quiz_answers');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('page_quiz_answers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('answer');
            $table->integer('page_quiz_question_id');
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
