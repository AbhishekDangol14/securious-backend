<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateThreatNeutralizedsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('threat_neutralizeds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('threat_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('company_id')->constrained()->nullable()->cascadeOnDelete();
            $table->unsignedBigInteger('customer_threat_id')->nullable();
            $table->foreign('customer_threat_id')->references('id')->on('analysis_customer_threats');
            $table->boolean('recheck_status')->default(0);
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
        Schema::dropIfExists('threat_neutralizeds');
    }
}
