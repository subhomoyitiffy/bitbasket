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
        Schema::create('lessonplan_conversions', function (Blueprint $table) {
            $table->id();
            $table->integer('lessonplan_id')->references('id')->on('lessonplans');
            $table->integer('student_id')->references('id')->on('students')->nullable();
            $table->integer('user_id')->references('id')->on('users')->nullable(); // SME for us
            $table->boolean('is_group')->default(false);
            $table->string('message')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lessonplan_conversions');
    }
};
