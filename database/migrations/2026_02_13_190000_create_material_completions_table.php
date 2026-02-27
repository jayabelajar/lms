<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('material_completions', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('course_material_id')->constrained('course_materials')->cascadeOnDelete();
            $table->foreignId('student_id')->constrained('users')->cascadeOnDelete();
            $table->dateTime('completed_at')->nullable();
            $table->timestamps();

            $table->unique(['course_material_id', 'student_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('material_completions');
    }
};
