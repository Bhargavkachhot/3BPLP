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
        Schema::create('role_module_permission', function (Blueprint $table) {
            $table->id(); 
            $table->tinyInteger('role_module_id')->nullable(); 
            $table->tinyInteger('read')->nullable();
            $table->tinyInteger('create')->nullable();
            $table->tinyInteger('update')->nullable();
            $table->tinyInteger('delete')->nullable();
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
        Schema::dropIfExists('role_module_permission');
    }
};
