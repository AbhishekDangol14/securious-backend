<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnalysisQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('analysis_questions', function (Blueprint $table) {
            $table->id();
            $table->string('question_type');
            $table->string('details_level');
            $table->text('video_link')->nullable();
            $table->boolean('show_if_industry')->default(0);
            $table->boolean('show_if_using_assets')->default(0);
            $table->boolean('show_if_company_size')->default(0);
            $table->boolean('display_if_conditions')->default(0);
            $table->boolean('automation_conditions')->default(0);
            $table->integer('order')->default(0);
            $table->unsignedBigInteger('threat_id');
            $table->foreign('threat_id')->references('id')->on('threats')->cascadeOnDelete();
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
        Schema::dropIfExists('analysis_questions');
    }
}
