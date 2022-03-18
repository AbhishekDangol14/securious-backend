<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRecommendationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recommendations', function (Blueprint $table) {
            $table->id();
            $table->boolean('is_automated')->default(0);
            $table->integer('points');
            $table->boolean('show_if_industry')->default(0);
            $table->boolean('show_if_company_size')->default(0);
            $table->boolean('display_if_conditions')->default(0);
            $table->unsignedBigInteger('threat_id');
            $table->foreign('threat_id')->references('id')->on('threats')->cascadeOnDelete();
            $table->integer('order')->default(0);
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
        Schema::dropIfExists('recommendations');
    }
}
