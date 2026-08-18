<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Booking
 */
class Booking extends Model
{
    protected $fillable = [
        'name', 'email', 'phone', 'gst_id', 'room_name', 'booking_date', 
        'start_time', 'end_time', 'clock_in', 'clock_out', 'total_price', 'razorpay_order_id', 
        'razorpay_payment_id', 'payment_status', 'approval_status',
        'nationality', 'user_type', 'stream', 'level', 'department',
        'primary_guest_name', 'no_of_persons', 'passport_number', 'visa_number', 'referral_attachment', 'admin_document', 'is_admin_read', 'booking_reason', 'residence_status', 'hall_name', 'reference_id', 'passport_visa_attachment', 'passport_attachment', 'visa_attachment', 'rejection_reason',
        'hod_approved_by', 'hod_approved_at', 'warden_approved_by', 'warden_approved_at',
        'principal_approved_by', 'principal_approved_at', 'admin_approved_by', 'admin_approved_at',
        'rejected_by', 'rejected_at', 'approval_remarks', 'principal_remarks',
        'discount_type', 'discount_value', 'original_price', 'discount_amount', 'discount_reason'
    ];

    protected $casts = [
        'clock_in' => 'datetime',
        'clock_out' => 'datetime',
        'hod_approved_at' => 'datetime',
        'warden_approved_at' => 'datetime',
        'principal_approved_at' => 'datetime',
        'admin_approved_at' => 'datetime',
        'rejected_at' => 'datetime',
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

    /**
     * Get carbon instance for clock_in
     */
    public function getClockInAttribute($value)
    {
        if (!empty($value)) {
            return \Carbon\Carbon::parse($value);
        }
        if (!empty($this->attributes['booking_date']) && !empty($this->attributes['start_time'])) {
            return \Carbon\Carbon::parse($this->attributes['booking_date'] . ' ' . $this->attributes['start_time']);
        }
        return null;
    }

    /**
     * Get carbon instance for clock_out
     */
    public function getClockOutAttribute($value)
    {
        if (!empty($value)) {
            return \Carbon\Carbon::parse($value);
        }
        if (!empty($this->attributes['booking_date']) && !empty($this->attributes['end_time'])) {
            $start = \Carbon\Carbon::parse($this->attributes['booking_date'] . ' ' . ($this->attributes['start_time'] ?? '00:00:00'));
            $end = \Carbon\Carbon::parse($this->attributes['booking_date'] . ' ' . $this->attributes['end_time']);
            if ($end->lte($start)) {
                $end->addDay();
            }
            return $end;
        }
        return null;
    }
}

