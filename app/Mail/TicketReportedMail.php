<?php

namespace App\Mail;

use App\Models\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TicketReportedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Ticket $ticket) {}

    public function envelope(): Envelope
    {
        $deskName = $this->ticket->desk ? $this->ticket->desk->name : 'Algemeen';

        return new Envelope(
            subject: "[WMS Schademelding] {$this->ticket->title} ({$deskName})",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.ticket-reported',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
