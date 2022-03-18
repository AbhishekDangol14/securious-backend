<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->cascadeOnDelete();
            $table->string('company_name');
            $table->string('company_website')->nullable();
            $table->string('company_size');
            $table->timestamp('last_assets_update')->nullable();
            $table->integer('recommendation_view_limit')->default(5);
            $table->text('business_address');
            $table->string('stripe_id')->nullable();
            $table->foreignId('company_role_id')->cascadeOnDelete();
            $table->foreignId('legal_role_id')->cascadeOnDelete();
            $table->unsignedBigInteger('industry_id');
            $table->foreign('industry_id')->references('id')->on('industries')->cascadeOnDelete();
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
        Schema::dropIfExists('companies');
    }
}
