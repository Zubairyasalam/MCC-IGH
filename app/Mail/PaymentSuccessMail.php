<?php

namespace App\Mail;

use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PaymentSuccessMail extends Mailable
{
    use Queueable, SerializesModels;

    public $booking;
    public $payment;
    public $primaryColor;
    public $pdf;

    /**
     * Create a new message instance.
     */
    public function __construct(Booking $booking, Payment $payment)
    {
        $this->booking = $booking;
        $this->payment = $payment;
        $this->primaryColor = \App\Models\Setting::where('key', 'primary_color')->value('value') ?? '#850f0f';
    }

    /**
     * Build the message.
     */
    public function build()
    {
        // Generate PDF in build method
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('emails.receipt_pdf', [
            'booking' => $this->booking,
            'payment' => $this->payment,
            'primaryColor' => $this->primaryColor
        ]);

        // Use the authenticated sender email — Gmail SMTP requires From == authenticated account
        $senderEmail = \App\Models\Setting::where('key', 'sender_email')->value('value')
                       ?? config('mail.from.address', 'prasathragul75@gmail.com');

        return $this->from($senderEmail, 'MCC IGH System')
                    ->subject('Official Invoice - MCC International Guest House')
                    ->view('emails.payment_success')
                    ->attachData($pdf->output(), 'Invoice_' . $this->payment->txnid . '.pdf', [
                        'mime' => 'application/pdf',
                    ]);
    }
}
