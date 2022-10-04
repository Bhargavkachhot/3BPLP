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
        Schema::create('users', function (Blueprint $table) {
            $table->id(); 
            $table->string('name')->nullable();
            $table->string('email')->nullable(); 
            $table->bigInteger('mobile_number')->nullable();
            $table->string('password')->nullable(); 
            $table->string('image')->nullable();
            $table->tinyInteger('is_active')->nullable()->default(1); 
            $table->tinyInteger('email_verified')->nullable()->default(0);
            $table->tinyInteger('is_accepted_tou')->nullable()->default(0); 
            $table->string('remember_token')->nullable(); 
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
        Schema::dropIfExists('setting');
    }
};
