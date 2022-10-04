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
        Schema::create('setting', function (Blueprint $table) {
            $table->id(); 
            $table->string('email')->nullable(); 
            $table->string('date_format')->nullable(); 
            $table->string('website_logo')->nullable();
            $table->bigInteger('phone')->nullable();
            $table->string('address')->nullable();
            $table->string('footer_message')->nullable(); 
            $table->string('smtpport')->nullable(); 
            $table->string('smtphost')->nullable();
            $table->string('smtpusername')->nullable();
            $table->string('smtppassword')->nullable();
            $table->string('from_name')->nullable();
            $table->string('from_email')->nullable(); 
            $table->string('support_email')->nullable();  
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
