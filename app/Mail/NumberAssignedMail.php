<?php

namespace App\Mail;

use App\Models\NumberPurchase;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NumberAssignedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public User $user,
        public NumberPurchase $purchase,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'A virtual number was assigned to your IQAB account');
    }

    public function content(): Content
    {
        return new Content(view: 'emails.number-assigned');
    }
}
