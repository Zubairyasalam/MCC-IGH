<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Booking
 */
class Booking extends Model
{    protected $fillable = [
        'name', 'email', 'phone', 'gst_id', 'room_name', 'booking_date', 
        'start_time', 'end_time', 'total_price', 'razorpay_order_id', 
        'razorpay_payment_id', 'payment_status', 'approval_status',
        'nationality', 'user_type', 'stream', 'level', 'department',
        'primary_guest_name', 'no_of_persons', 'passport_number', 'visa_number', 'referral_attachment', 'is_admin_read', 'booking_reason', 'residence_status', 'reference_id', 'passport_visa_attachment'
    ];
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($booking) {
            if (empty($booking->reference_id)) {
                do {
                    $ref = 'REF-' . strtoupper(bin2hex(random_bytes(3)));
                } while (self::where('reference_id', $ref)->exists());

                $booking->reference_id = $ref;
            }
        });

        static::saving(function ($booking) {
            try {
                if (\Illuminate\Support\Facades\Schema::hasTable($booking->getTable())) {
                    $tableColumns = \Illuminate\Support\Facades\Schema::getColumnListing($booking->getTable());
                    if (!empty($tableColumns)) {
                        $attributes = $booking->getAttributes();
                        foreach ($attributes as $key => $value) {
                            if (!in_array($key, $tableColumns)) {
                                unset($booking->attributes[$key]);
                            }
                        }
                    }
                }
            } catch (\Throwable $e) {
                // Ignore errors if schema listing check fails
            }
        });
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function paymentLinks()
    {
        return $this->hasMany(PaymentLink::class);
    }

    /**
     * Format room name for display
     */
    public function getRoomNameAttribute($value)
    {
        return ucwords(str_replace(['-', '_'], ' ', $value));
    }

    /**
     * Set the room name attribute
     */
    public function setRoomNameAttribute($value)
    {
        $this->attributes['room_name'] = $value;
    }
}

