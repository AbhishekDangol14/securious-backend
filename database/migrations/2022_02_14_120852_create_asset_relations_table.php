<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssetRelationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('asset_relations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('solution_partner_product_id');
            $table->foreign('solution_partner_product_id')->references('id')->on('solution_partner_products')->cascadeOnDelete();
            $table->unsignedBigInteger('related_id');
            $table->string('related_type');
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
        Schema::dropIfExists('asset_relations');
    }
}
