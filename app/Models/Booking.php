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

    /**
     * Check if any room in $selectedRooms overlaps with an existing non-rejected booking
     * during the period [$clockIn, $clockOut].
     *
     * @param array|string $selectedRooms
     * @param \Carbon\Carbon $clockIn
     * @param \Carbon\Carbon $clockOut
     * @param int|null $excludeBookingId
     * @return string|null Returns the conflicting room name if found, else null.
     */
    public static function findConflictingRoom($selectedRooms, $clockIn, $clockOut, $excludeBookingId = null)
    {
        if (is_string($selectedRooms)) {
            $selectedRooms = array_filter(array_map('trim', explode(',', $selectedRooms)));
        }

        if (empty($selectedRooms)) {
            return null;
        }

        $clockInStr = $clockIn->toDateTimeString();
        $clockOutStr = $clockOut->toDateTimeString();

        // Query candidate non-rejected bookings whose stay interval overlaps with [$clockIn, $clockOut]
        $query = self::where('approval_status', '!=', 'Rejected');

        if ($excludeBookingId) {
            $query->where('id', '!=', $excludeBookingId);
        }

        $candidateBookings = $query->where(function ($q) use ($clockIn, $clockOut, $clockInStr, $clockOutStr) {
            // Interval overlap logic: existing_start < new_end AND existing_end > new_start
            $q->where(function ($sub) use ($clockInStr, $clockOutStr) {
                $sub->whereNotNull('clock_in')
                    ->whereNotNull('clock_out')
                    ->where('clock_in', '<', $clockOutStr)
                    ->where('clock_out', '>', $clockInStr);
            })
            // Fallback for legacy records missing clock_in/clock_out
            ->orWhere(function ($sub) use ($clockIn, $clockOut) {
                $sub->where(function ($leg) {
                    $leg->whereNull('clock_in')->orWhereNull('clock_out');
                })
                ->where('booking_date', '<=', $clockOut->toDateString())
                ->where('booking_date', '>=', $clockIn->toDateString());
            });
        })->get(['id', 'room_name', 'clock_in', 'clock_out', 'booking_date', 'start_time', 'end_time']);

        // Helper to normalize room name for accurate comparison
        $normalizeRoomToken = function ($str) {
            $s = strtolower(trim($str));
            $s = str_replace(['-', '_'], ' ', $s);
            $s = preg_replace('/\s+/', ' ', $s);
            return $s;
        };

        foreach ($selectedRooms as $reqRoom) {
            $normReq = $normalizeRoomToken($reqRoom);
            $digitsReq = preg_replace('/[^0-9]/', '', $normReq);

            foreach ($candidateBookings as $booking) {
                $existingRooms = array_filter(array_map('trim', explode(',', $booking->room_name)));
                foreach ($existingRooms as $existRoom) {
                    $normExist = $normalizeRoomToken($existRoom);
                    $digitsExist = preg_replace('/[^0-9]/', '', $normExist);

                    // A) Exact string match after normalization
                    if ($normReq === $normExist) {
                        return $reqRoom;
                    }

                    // B) Numeric room matching (e.g. "Standard Room 4" vs "4" or "Standard Room 4")
                    if (!empty($digitsReq) && !empty($digitsExist) && $digitsReq === $digitsExist) {
                        if ($normReq === $normExist || str_contains($normReq, $normExist) || str_contains($normExist, $normReq)) {
                            return $reqRoom;
                        }
                    }

                    // C) Special facilities slug match (Conference Room, Glass Room, Suite Room)
                    $slugReq = str_replace(' ', '', $normReq);
                    $slugExist = str_replace(' ', '', $normExist);
                    if ($slugReq === $slugExist) {
                        return $reqRoom;
                    }
                }
            }
        }

        return null;
    }
}

