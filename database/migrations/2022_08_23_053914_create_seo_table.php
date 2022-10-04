<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seo', function (Blueprint $table) {
            $table->id();
            $table->integer('primary_category_id')->nullable(); 
            $table->integer('subcategory_id')->nullable(); 
            $table->integer('product_category_id')->nullable();  
            $table->string('seo_headline_one')->nullable(); 
            $table->string('seo_description_one')->nullable(); 
            $table->string('seo_headline_two')->nullable(); 
            $table->string('seo_description_two')->nullable(); 
            $table->string('seo_headline_three')->nullable(); 
            $table->string('seo_description_three')->nullable(); 
            $table->string('seo_description_other')->nullable(); 
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
        Schema::dropIfExists('seo');
    }
};
