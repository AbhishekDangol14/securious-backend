<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRecommendationQuestionAnswersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recommendation_question_answers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('question_id');
            $table->unsignedBigInteger('recommendation_id');
            $table->foreign('question_id')->references('id')->on('analysis_questions')->cascadeOnDelete();
            $table->foreign('recommendation_id')->references('id')->on('recommendations')->cascadeOnDelete();
            $table->unsignedBigInteger('answer_id');
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
        Schema::dropIfExists('recommendation_question_answers');
    }
}
