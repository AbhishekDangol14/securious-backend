<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImportantIndustriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('important_industries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('industry_id');
            $table->foreign('industry_id')->references('id')->on('industries')->cascadeOnDelete();
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
        Schema::dropIfExists('important_industries');
    }
}
