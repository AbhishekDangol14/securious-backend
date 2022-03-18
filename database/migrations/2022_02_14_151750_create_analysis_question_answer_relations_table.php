<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnalysisQuestionAnswerRelationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('analysis_question_answer_relations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('analysis_question_id');
            $table->unsignedBigInteger('answer_id');
            $table->foreign('analysis_question_id')->references('id')->on('analysis_questions')->cascadeOnDelete();
            $table->foreign('answer_id')->references('id')->on('analysis_question_answers')->cascadeOnDelete();
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
        Schema::dropIfExists('analysis_question_answer_relations');
    }
}
