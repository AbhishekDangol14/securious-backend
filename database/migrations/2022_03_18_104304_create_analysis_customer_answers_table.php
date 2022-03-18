<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnalysisCustomerAnswersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('analysis_customer_answers', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreignId('threat_id')->constrained('threats')->cascadeOnDelete();
            $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete();
            $table->bigInteger('customer_threat_id')->unsigned();
            $table->foreign('customer_threat_id')->references('id')->on('analysis_customer_threats')->cascadeOnDelete();
            $table->bigInteger('customer_question_id')->unsigned();
            $table->bigInteger('question_id')->unsigned();
            $table->foreign('question_id')->references('id')->on('analysis_questions')->cascadeOnDelete();
            $table->foreignId('answer_id')->constrained('analysis_question_answers')->nullable();
            $table->string('score', 191)->nullable();
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
        Schema::dropIfExists('analysis_customer_answers');
    }
}
