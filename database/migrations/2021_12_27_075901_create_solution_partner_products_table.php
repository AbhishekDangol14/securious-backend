<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSolutionPartnerProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('solution_partner_products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('solution_partner_id');
            $table->boolean('is_solution_partner')->default(0);
            $table->boolean('is_company_asset')->default(0);
            $table->text('product_link')->nullable();
            $table->boolean('show_if_industry')->default(0);
            $table->boolean('show_if_company_size')->default(0);
            $table->boolean('is_active')->default(0);
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
        Schema::dropIfExists('solution_partner_products');
    }
}
