<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Attachment;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class UnverifiedUsersReport extends Mailable
{
    use Queueable, SerializesModels;

    public string $csv;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($csv)
    {
        $this->csv = $csv;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Unverified Users Report',
        );
    }

    /**
     * Get the message content definition.
     *
     * @return Content
     */
    public function content()
    {
        return new Content(
            view: 'email.removeusers'
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments()
    {
        return [Attachment::fromPath($this->csv)->withMime('text/csv')];
    }
}
