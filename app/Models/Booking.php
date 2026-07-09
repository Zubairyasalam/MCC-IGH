<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'name', 'email', 'phone', 'gst_id', 'room_name', 'booking_date', 
        'start_time', 'end_time', 'total_price', 'razorpay_order_id', 
        'razorpay_payment_id', 'payment_status', 'approval_status',
        'nationality', 'user_type', 'stream', 'level', 'department',
        'primary_guest_name', 'no_of_persons', 'passport_number', 'visa_number', 'referral_attachment', 'is_admin_read', 'booking_reason'
    ];

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
}
