<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerRecommendationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_recommendations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('recommendation_id')->nullable();
            $table->foreign('recommendation_id')->references('id')->on('recommendations')->onDelete('cascade');
            $table->unsignedBigInteger('threat_id')->nullable();
            $table->foreign('threat_id')->references('id')->on('threats');
            $table->unsignedBigInteger('solution_partner_products_id')->nullable();
            $table->foreign('solution_partner_products_id')->references('id')->on('solution_partner_products')->onDelete('cascade');
            $table->unsignedBigInteger('company_id')->nullable();
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->foreign('customer_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('customer_threat_id')->nullable();
            $table->foreign('customer_threat_id')->references('id')->on('analysis_customer_threats');
            $table->enum('status', ['ignored','not_started', 'in_progress', 'completed']);
            $table->text('customer_answer')->nullable();
            $table->boolean('is_recommended')->default(0);
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
        Schema::dropIfExists('customer_recommendations');
    }
}
