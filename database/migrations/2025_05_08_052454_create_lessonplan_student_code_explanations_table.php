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
        Schema::create('lessonplan_student_code_explanations', function (Blueprint $table) {
            $table->id();
            $table->integer('lessonplan_id')->references('id')->on('lessonplans');
            $table->integer('student_id')->references('id')->on('students');
            $table->text('code');
            $table->text('explanation');
            $table->boolean('edit_plan')->default(false);
            $table->boolean('published')->default(false);
            $table->tinyInteger('status')->default(1);//[0->inactive, 1->active, 4->archived]
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lessonplan_student_code_explanations');
    }
};
