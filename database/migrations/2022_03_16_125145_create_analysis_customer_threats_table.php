<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnalysisCustomerThreatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('analysis_customer_threats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->foreignId('threat_id')->constrained()->cascadeOnDelete();
            $table->unsignedBigInteger('last_answered_question')->nullable();
            $table->foreign('last_answered_question')->references('id')->on('analysis_questions');
            $table->dateTime('started_at')->nullable();
            $table->dateTime('completed_at')->nullable();
            $table->dateTime('restarted_at')->nullable();
            $table->boolean('is_completed')->default(false);
            $table->unsignedBigInteger('time_to_analysis')->nullable();
            $table->unsignedBigInteger('total_inactive')->nullable();
            $table->dateTime('last_inactive_at')->nullable();
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
        Schema::dropIfExists('analysis_customer_threats');
    }
}
