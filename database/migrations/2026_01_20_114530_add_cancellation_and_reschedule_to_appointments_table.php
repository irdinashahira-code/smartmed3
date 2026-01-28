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
        Schema::table('appointments', function (Blueprint $table) {
            $table->string('cancellation_status')->nullable()->after('status'); // pending, approved
            $table->string('reschedule_status')->nullable()->after('cancellation_status'); // pending, approved
            $table->json('reschedule_data')->nullable()->after('reschedule_status'); // Stores new appointment details
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropColumn(['cancellation_status', 'reschedule_status', 'reschedule_data']);
        });
    }
};
