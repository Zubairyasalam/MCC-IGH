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
        if (Schema::hasTable('bookings') && !Schema::hasColumn('bookings', 'admin_document')) {
            Schema::table('bookings', function (Blueprint $table) {
                $table->string('admin_document')->nullable()->after('referral_attachment');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('bookings') && Schema::hasColumn('bookings', 'admin_document')) {
            Schema::table('bookings', function (Blueprint $table) {
                $table->dropColumn('admin_document');
            });
        }
    }
};
