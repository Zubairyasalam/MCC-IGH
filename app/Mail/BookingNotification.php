<?php

namespace App\Mail;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BookingNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $booking;
    public $primaryColor;
    public $approveUrl;
    public $rejectUrl;

    /**
     * Create a new message instance.
     */
    public function __construct(Booking $booking, $approveUrl = null, $rejectUrl = null)
    {
        $this->booking = $booking;
        $this->primaryColor = \App\Models\Setting::where('key', 'primary_color')->value('value') ?? '#850f0f';
        $this->approveUrl = $approveUrl ?: route('admin.bookings.approve.get', $booking->id);
        $this->rejectUrl = $rejectUrl ?: route('admin.bookings.reject.get', $booking->id);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new \Illuminate\Mail\Mailables\Address(
                'noreply@mccigh.com',
                'MCC IGH System'
            ),
            subject: 'New Booking Request: ' . $this->booking->room_name,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.booking_notification',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        $attachments = [];

        if ($this->booking->referral_attachment) {
            $attachments[] = \Illuminate\Mail\Mailables\Attachment::fromPath(storage_path('app/public/' . $this->booking->referral_attachment))
                ->as('referral_document.' . pathinfo($this->booking->referral_attachment, PATHINFO_EXTENSION));
        }

        if ($this->booking->passport_visa_attachment) {
            $attachments[] = \Illuminate\Mail\Mailables\Attachment::fromPath(storage_path('app/public/' . $this->booking->passport_visa_attachment))
                ->as('passport_visa_document.' . pathinfo($this->booking->passport_visa_attachment, PATHINFO_EXTENSION));
        }

        return $attachments;
    }
}
