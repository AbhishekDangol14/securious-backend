<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateThreatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('threats', function (Blueprint $table) {
            $table->id();
            $table->integer('estimated_time_in_minutes');
            $table->text('video_link')->nullable();
            $table->boolean('is_always_important')->default(0);
            $table->boolean('important_if_industry_id')->default(0);
            $table->boolean('important_if_company_size')->default(0);
            $table->boolean('is_display_active_always')->default(0);
            $table->boolean('show_if_industry')->default(0);
            $table->boolean('show_if_company_size')->default(0);
            $table->boolean('show_if_using_asset')->default(0);
            $table->string('status');
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
        Schema::dropIfExists('threats');
    }
}
