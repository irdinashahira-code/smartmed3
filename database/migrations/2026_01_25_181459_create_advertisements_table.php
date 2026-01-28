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
        Schema::create('advertisements', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->enum('type', ['preventive', 'health_tip', 'service_promotion', 'doctor_highlight']);
            $table->text('content');
            $table->string('image_path')->nullable();
            $table->string('cta_text')->nullable();
            $table->string('cta_link')->nullable();
            $table->integer('target_age_min')->nullable();
            $table->integer('target_age_max')->nullable();
            $table->enum('target_gender', ['male', 'female', 'all'])->default('all');
            $table->json('target_conditions')->nullable();
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('priority')->default(1);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('advertisements');
    }
};
