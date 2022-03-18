<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRecommendationAnalysisQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recommendation_analysis_questions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('analysis_question_id');
            $table->unsignedBigInteger('recommendation_id');
            $table->foreign('analysis_question_id')->references('id')->on('analysis_questions')->cascadeOnDelete();
            $table->foreign('recommendation_id')->references('id')->on('recommendations')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('recommendation_analysis_questions');
    }
}
