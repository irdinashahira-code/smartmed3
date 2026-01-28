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
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // Patient
            $table->unsignedBigInteger('doctor_id')->nullable(); // Doctor
            $table->date('appointment_date');
            $table->time('appointment_time');
            $table->string('type')->nullable(); // vaccination, full body checkup, etc.
            $table->text('reason')->nullable();
            $table->decimal('weight', 5, 2)->nullable();
            $table->string('status')->default('booked'); // booked, completed, cancelled
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('doctor_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
