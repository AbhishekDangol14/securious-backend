<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnalysisQuestionAnswersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('analysis_question_answers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('solution_partner_product_id')->nullable();
            $table->foreign('solution_partner_product_id')->references('id')->on('solution_partner_products')->cascadeOnDelete();
            $table->integer('order');
            $table->unsignedBigInteger('analysis_question_id');
            $table->foreign('analysis_question_id')->references('id')->on('analysis_questions')->cascadeOnDelete();
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
        Schema::dropIfExists('analysis_question_answers');
    }
}
