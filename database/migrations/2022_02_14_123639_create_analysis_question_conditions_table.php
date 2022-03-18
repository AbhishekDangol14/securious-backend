<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnalysisQuestionConditionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('analysis_question_conditions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('analysis_question_id');
            $table->foreign('analysis_question_id')->references('id')->on('analysis_questions')->cascadeOnDelete();
            $table->unsignedBigInteger('answer_id');
            $table->foreign('answer_id')->references('id')->on('analysis_question_answers')->cascadeOnDelete();
            $table->boolean('rule');
            $table->boolean('is_equal_to');
            $table->unsignedBigInteger('question_id');
            $table->foreign('question_id')->references('id')->on('analysis_questions')->cascadeOnDelete();
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
        Schema::dropIfExists('analysis_question_conditions');
    }
}
