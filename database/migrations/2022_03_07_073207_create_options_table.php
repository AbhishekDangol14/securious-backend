<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('options', function (Blueprint $table) {
            $table->id();
            $table->string('key')->comment('To identify row or get data using key');
            $table->longtext('value')->comment('Store JSON, small medium large text, etc');
            $table->unsignedBigInteger('reference_id')->default(0)->comment('Any table`s ID');
            $table->unsignedBigInteger('parent_id')->default(0)->comment('Option table`s ID or if you find any other use of this column then go for it');
            $table->string('type')->nullable()->comment('To select all data using type or treat as a category');
            $table->mediumText('description')->nullable()->comment('Add description regarding table row used for or any other purpose');
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
        Schema::dropIfExists('options');
    }
}
