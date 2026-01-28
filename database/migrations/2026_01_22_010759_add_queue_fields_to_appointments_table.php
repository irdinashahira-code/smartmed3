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
            $table->integer('queue_number')->nullable()->after('status');
            $table->enum('queue_status', ['waiting', 'called', 'consulting', 'completed', 'skipped'])->nullable()->after('queue_number');
            $table->timestamp('checked_in_at')->nullable()->after('queue_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropColumn(['queue_number', 'queue_status', 'checked_in_at']);
        });
    }
};
