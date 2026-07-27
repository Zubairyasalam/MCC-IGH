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
        $senderEmail = \App\Models\Setting::where('key', 'sender_email')->value('value')
                       ?? config('mail.from.address', 'prasathragul75@gmail.com');

        return new Envelope(
            from: new \Illuminate\Mail\Mailables\Address(
                $senderEmail,
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

        if (!empty($this->booking->referral_attachment)) {
            $path = storage_path('app/public/' . $this->booking->referral_attachment);
            if (file_exists($path)) {
                $attachments[] = \Illuminate\Mail\Mailables\Attachment::fromPath($path)
                    ->as('referral_document.' . pathinfo($this->booking->referral_attachment, PATHINFO_EXTENSION));
            }
        }

        if (!empty($this->booking->passport_visa_attachment)) {
            $path = storage_path('app/public/' . $this->booking->passport_visa_attachment);
            if (file_exists($path)) {
                $attachments[] = \Illuminate\Mail\Mailables\Attachment::fromPath($path)
                    ->as('passport_visa_document.' . pathinfo($this->booking->passport_visa_attachment, PATHINFO_EXTENSION));
            }
        }

        return $attachments;
    }
}
