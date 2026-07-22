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
        Schema::table('bookings', function (Blueprint $table) {
            $table->string('reference_id')->nullable()->after('id');
        });

        // Populate existing bookings
        $bookings = DB::table('bookings')->get();
        foreach ($bookings as $booking) {
            $refId = 'REF-' . str_pad($booking->id + 10000, 6, '0', STR_PAD_LEFT);
            DB::table('bookings')->where('id', $booking->id)->update(['reference_id' => $refId]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn('reference_id');
        });
    }
};
