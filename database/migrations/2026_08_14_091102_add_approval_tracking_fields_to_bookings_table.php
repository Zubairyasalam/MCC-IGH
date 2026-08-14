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
            $table->string('hod_approved_by')->nullable()->after('approval_status');
            $table->timestamp('hod_approved_at')->nullable()->after('hod_approved_by');
            $table->string('warden_approved_by')->nullable()->after('hod_approved_at');
            $table->timestamp('warden_approved_at')->nullable()->after('warden_approved_by');
            $table->string('principal_approved_by')->nullable()->after('warden_approved_at');
            $table->timestamp('principal_approved_at')->nullable()->after('principal_approved_by');
            $table->string('admin_approved_by')->nullable()->after('principal_approved_at');
            $table->timestamp('admin_approved_at')->nullable()->after('admin_approved_by');
            $table->string('rejected_by')->nullable()->after('admin_approved_at');
            $table->timestamp('rejected_at')->nullable()->after('rejected_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn([
                'hod_approved_by', 'hod_approved_at',
                'warden_approved_by', 'warden_approved_at',
                'principal_approved_by', 'principal_approved_at',
                'admin_approved_by', 'admin_approved_at',
                'rejected_by', 'rejected_at'
            ]);
        });
    }
};
