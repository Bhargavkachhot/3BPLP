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
        Schema::create('example_products', function (Blueprint $table) {
            $table->id();
            $table->integer('primary_category_id')->nullable(); 
            $table->integer('subcategory_id')->nullable();
            $table->integer('product_category_id')->nullable(); 
            $table->string('example_product')->nullable();  
            $table->integer('position')->nullable(); 
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
        Schema::dropIfExists('example_products');
    }
};
