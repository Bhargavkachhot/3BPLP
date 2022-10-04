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
        Schema::create('user_front', function (Blueprint $table) {
            $table->id();  
            $table->string('name')->nullable();
            $table->bigInteger('phone_number')->nullable(); 
            $table->string('email');  
            $table->string('password')->nullable(); 
            $table->tinyInteger('company_size')->nullable();  
            $table->string('company_address')->nullable(); 
            $table->string('zip')->nullable();    
            $table->string('country')->nullable();  
            $table->string('profile_picture')->nullable(); 
            $table->bigInteger('vat_number')->nullable();  
            $table->bigInteger('product_category')->nullable();
            $table->tinyInteger('status')->nullable()->default(1); 
            $table->tinyInteger('user_role')->nullable()->default(1); 
            $table->string('remember_token')->nullable(); 
            $table->tinyInteger('email_verified')->nullable(); 
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
        Schema::dropIfExists('user_front');
    }
};
