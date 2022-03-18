<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDescriptionForAssetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('description_for_assets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('description_for_id');
            $table->foreign('description_for_id')->references('id')->on('description_fors')->cascadeOnDelete();
            $table->unsignedBigInteger('solution_partner_product_id');
            $table->foreign('solution_partner_product_id')->references('id')->on('solution_partners')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     *
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('description_for_assets');
    }
}
