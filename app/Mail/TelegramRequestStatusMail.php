<?php

namespace App\Mail;

use App\Models\TelegramServiceRequest;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TelegramRequestStatusMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public User $user,
        public TelegramServiceRequest $serviceRequest,
    ) {}

    public function envelope(): Envelope
    {
        $ok = $this->serviceRequest->status->value === 'completed';

        return new Envelope(
            subject: $ok
                ? 'Your Telegram service result is ready — IQAB'
                : 'Your Telegram service request needs attention — IQAB'
        );
    }

    public function content(): Content
    {
        return new Content(view: 'emails.telegram-status');
    }
}
