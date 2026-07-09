<?php

namespace App\Mail;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BookingApproved extends Mailable
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
            subject: 'Reservation Confirmed - MCC IGH: ' . $this->booking->room_name,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.booking_approved',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        // Generate receipt PDF dynamically
        $booking = $this->booking;
        $primaryColor = $this->primaryColor;
        $pdf = \Pdf::loadView('emails.receipt_pdf', compact('booking', 'primaryColor'));
        
        return [
            \Illuminate\Mail\Mailables\Attachment::fromData(fn () => $pdf->output(), 'MCC_IGH_Receipt.pdf')
                ->withMime('application/pdf'),
        ];
    }
}
