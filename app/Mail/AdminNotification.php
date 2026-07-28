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

        if (!empty($this->booking->referral_attachment)) {
            $path = storage_path('app/public/' . $this->booking->referral_attachment);
            if (file_exists($path)) {
                $attachments[] = \Illuminate\Mail\Mailables\Attachment::fromPath($path)
                    ->as('referral_document.' . pathinfo($this->booking->referral_attachment, PATHINFO_EXTENSION));
            }
        }

        if (!empty($this->booking->passport_attachment)) {
            $path = storage_path('app/public/' . $this->booking->passport_attachment);
            if (file_exists($path)) {
                $attachments[] = \Illuminate\Mail\Mailables\Attachment::fromPath($path)
                    ->as('passport_document.' . pathinfo($this->booking->passport_attachment, PATHINFO_EXTENSION));
            }
        }

        if (!empty($this->booking->visa_attachment)) {
            $path = storage_path('app/public/' . $this->booking->visa_attachment);
            if (file_exists($path)) {
                $attachments[] = \Illuminate\Mail\Mailables\Attachment::fromPath($path)
                    ->as('visa_document.' . pathinfo($this->booking->visa_attachment, PATHINFO_EXTENSION));
            }
        }

        if (!empty($this->booking->passport_visa_attachment) && empty($this->booking->passport_attachment) && empty($this->booking->visa_attachment)) {
            $path = storage_path('app/public/' . $this->booking->passport_visa_attachment);
            if (file_exists($path)) {
                $attachments[] = \Illuminate\Mail\Mailables\Attachment::fromPath($path)
                    ->as('passport_visa_document.' . pathinfo($this->booking->passport_visa_attachment, PATHINFO_EXTENSION));
            }
        }

        return $attachments;
    }
}
