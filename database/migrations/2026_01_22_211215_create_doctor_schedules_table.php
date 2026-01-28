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
        Schema::create('doctor_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // The doctor
            $table->unsignedTinyInteger('day_of_week'); // 0 = Sunday, 1 = Monday, ..., 6 = Saturday
            $table->time('start_time');
            $table->time('end_time');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            // Unique constraint to prevent overlapping schedules for the same day (simplified)
            // Ideally we'd have more complex checks, but for now assuming one block per day or handled by app logic
            // $table->unique(['user_id', 'day_of_week']); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doctor_schedules');
    }
};
