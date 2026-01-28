<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE appointments MODIFY COLUMN queue_status ENUM('waiting', 'called', 'arrived', 'consulting', 'completed', 'skipped') NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert to original enum (warning: 'arrived' status data might be lost or cause issues if not handled)
        // We will map 'arrived' back to 'called' before reverting to avoid error?
        // For simplicity in down(), we just revert the definition. Data with 'arrived' might become empty string or 0 depending on strict mode.
        DB::table('appointments')->where('queue_status', 'arrived')->update(['queue_status' => 'called']);
        DB::statement("ALTER TABLE appointments MODIFY COLUMN queue_status ENUM('waiting', 'called', 'consulting', 'completed', 'skipped') NULL");
    }
};
