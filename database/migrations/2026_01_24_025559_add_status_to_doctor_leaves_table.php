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
        Schema::table('doctor_leaves', function (Blueprint $table) {
            $table->string('status')->default('pending')->after('reason'); // pending, approved, rejected
        });

        // Set existing leaves to approved so we don't disrupt current schedules
        DB::table('doctor_leaves')->update(['status' => 'approved']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('doctor_leaves', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
