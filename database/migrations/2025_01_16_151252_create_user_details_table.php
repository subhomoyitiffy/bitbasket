<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_details', function (Blueprint $table) {
            $table->id();
            $table->foreign('user_id')->references('id')->on('users');
            $table->string('country');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email');
            $table->string('country_code');
            $table->string('phone');
            $table->integer('city_id')->nullable();
            $table->string('emarati')->nullable();
            $table->string('business_license')->nullable();
            $table->string('tax_registration_number')->nullable();
            $table->string('company_type')->nullable();
            $table->string('employer_identification_no')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_details');
    }
};
