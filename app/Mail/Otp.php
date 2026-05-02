<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class Otp extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Menggunakan Constructor Property Promotion (PHP 8+)
     */
    public function __construct(
        public string $name,
        public string $email,
        public string $otp
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Kode Verifikasi OTP - ' . config('app.name'),
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.otp',
            // Properti public $name, $email, $otp otomatis terkirim ke view
        );
    }

    public function attachments(): array
    {
        return [];
    }
}