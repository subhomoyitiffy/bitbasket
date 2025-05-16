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
            $table->integer('user_id');
            $table->string('country', 10);
            $table->string('first_name', 50);
            $table->string('last_name', 50);
            $table->string('email', 100)->unique();
            $table->string('country_code', 5)->unique()->nullable();
            $table->string('phone', 15)->unique()->nullable();
            $table->integer('city_id')->nullable();
            $table->string('emarati', 10)->nullable();
            $table->string('business_license')->nullable();
            $table->string('tax_registration_number')->nullable();
            $table->string('company_type')->nullable();
            $table->string('employer_identification_no')->nullable();
            $table->string('stripe_cust_id')->nullable();
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
