<?php

namespace App\Mail;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AdminNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $booking;
    public $primaryColor;

    /**
     * Create a new message instance.
     */
    public function __construct(Booking $booking)
    {
        $this->booking = $booking;
        $this->primaryColor = \App\Models\Setting::where('key', 'primary_color')->value('value') ?? '#850f0f';
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Principal Approved Booking: ' . $this->booking->room_name,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.admin_notification',
        );
    }

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
