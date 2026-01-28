<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone_number')->nullable()->after('email');
            $table->string('ic_number')->nullable()->after('phone_number');
            $table->date('date_of_birth')->nullable()->after('ic_number');
            $table->string('address')->nullable()->after('date_of_birth');
            $table->string('city')->nullable()->after('address');
            $table->string('state')->nullable()->after('city');
            $table->string('postcode')->nullable()->after('state');
            $table->string('staff_id')->nullable()->after('postcode');
            $table->string('secret_key')->nullable()->after('staff_id');
            $table->string('specialization')->nullable()->after('secret_key');
            $table->string('blood_type')->nullable()->after('specialization');
            $table->text('allergies')->nullable()->after('blood_type');
            $table->string('emergency_contact_name')->nullable()->after('allergies');
            $table->string('emergency_contact_phone')->nullable()->after('emergency_contact_name');
            $table->string('emergency_contact_relationship')->nullable()->after('emergency_contact_phone');
            $table->string('role')->default('patient')->after('emergency_contact_relationship');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'phone_number',
                'ic_number',
                'date_of_birth',
                'address',
                'city',
                'state',
                'postcode',
                'staff_id',
                'secret_key',
                'specialization',
                'blood_type',
                'allergies',
                'emergency_contact_name',
                'emergency_contact_phone',
                'emergency_contact_relationship',
                'role',
            ]);
        });
    }
};

