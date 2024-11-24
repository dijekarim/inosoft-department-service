<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('departments', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->date('start_reg');
            $table->date('end_reg');
            $table->integer('quota')->default(100); // Maximum number of students
            $table->decimal('min_math_grade', 3, 2)->default(8.0); // Minimum math grade required
            $table->decimal('min_science_grade', 3, 2)->default(8.0); // Minimum science grade required
            $table->integer('total_students_registered')->min(0)->default(0);
            $table->timestamps();

            $table->index('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('departments');
    }
};
