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
        Schema::create('subcategories', function (Blueprint $table) {
            $table->id();
            $table->integer('primary_category_id')->nullable();
            $table->string('artical_number')->nullable();
            $table->string('subcategory')->nullable();
            $table->string('url_key')->nullable();
            $table->string('full_url_key')->nullable();
            $table->integer('position')->nullable();
            $table->string('meta_title')->nullable(); 
            $table->string('meta_description')->nullable(); 
            $table->text('description')->nullable(); 
            $table->string('icon')->nullable();   
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
        Schema::dropIfExists('subcategories');
    }
};
