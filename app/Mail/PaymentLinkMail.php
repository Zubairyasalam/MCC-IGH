<?php

namespace App\Mail;

use App\Models\Booking;
use App\Models\PaymentLink;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PaymentLinkMail extends Mailable
{
    use Queueable, SerializesModels;

    public $booking;
    public $paymentLink;
    public $paymentUrl;
    public $primaryColor;

    /**
     * Create a new message instance.
     */
    public function __construct(Booking $booking, PaymentLink $paymentLink)
    {
        $this->booking = $booking;
        $this->paymentLink = $paymentLink;
        $this->paymentUrl = route('payment.show', ['token' => $paymentLink->token]);
        $this->primaryColor = \App\Models\Setting::where('key', 'primary_color')->value('value') ?? '#850f0f';
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
            subject: 'Payment Required for Your Reservation - MCC IGH',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.payment_link',
        );
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        return [];
    }
}
