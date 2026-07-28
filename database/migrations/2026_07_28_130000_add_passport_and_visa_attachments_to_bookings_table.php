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
        Schema::table('bookings', function (Blueprint $table) {
            if (!Schema::hasColumn('bookings', 'passport_attachment')) {
                $table->string('passport_attachment')->nullable()->after('visa_number');
            }
            if (!Schema::hasColumn('bookings', 'visa_attachment')) {
                $table->string('visa_attachment')->nullable()->after('passport_attachment');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            if (Schema::hasColumn('bookings', 'passport_attachment')) {
                $table->dropColumn('passport_attachment');
            }
            if (Schema::hasColumn('bookings', 'visa_attachment')) {
                $table->dropColumn('visa_attachment');
            }
        });
    }
};
