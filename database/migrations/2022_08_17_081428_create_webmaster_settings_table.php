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
        Schema::create('webmaster_settings', function (Blueprint $table) {
            $table->id();  
            $table->string('mail_driver');
            $table->string('mail_host');
            $table->string('mail_port');
            $table->string('mail_username');
            $table->string('mail_password');
            $table->string('mail_encryption');
            $table->string('mail_no_replay');
            $table->string('copyright_en');
            $table->string('site_title_en'); 
            $table->string('mail_title')->nullable();
            $table->longText('mail_template')->nullable(); 
            $table->string('timezone');
            $table->string('version',20)->nullable(); 
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable(); 
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
        Schema::dropIfExists('webmaster_settings');
    }
};
