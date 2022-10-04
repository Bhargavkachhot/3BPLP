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
        Schema::create('primary_categories', function (Blueprint $table) {
            $table->id();
            $table->string('category_name')->nullable();
            $table->string('url_key')->nullable();
            $table->tinyInteger('position')->nullable();
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
        Schema::dropIfExists('primary_categories');
    }
};
