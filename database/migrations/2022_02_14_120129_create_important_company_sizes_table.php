<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImportantCompanySizesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('important_company_sizes', function (Blueprint $table) {
            $table->id();
            $table->integer('company_size_from');
            $table->integer('company_size_to');
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
        Schema::dropIfExists('important_company_sizes');
    }
}
