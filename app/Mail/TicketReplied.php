<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Contracts\Queue\ShouldQueue;

class TicketReplied extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $subject, $user, $message;

    /**
     * Create a new message instance.
     */
    public function __construct(protected $ticketId, $subject, User $user, $message)
    {
        $this->subject = $subject;

        $this->user = $user;

        $this->message = $message;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $subject = '[Ticket ID: ' . $this->ticketId . ']' . ' ' . $this->subject;

        return new Envelope(
            subject: $subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'mail.ticketReply',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
