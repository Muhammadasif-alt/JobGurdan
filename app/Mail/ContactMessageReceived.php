<?php

namespace App\Mail;

use App\Models\ContactMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * Sent to the site's contact address whenever someone submits the form.
 *
 * The form previously only wrote a row to the database, so a message sat in
 * the admin panel until somebody thought to look.
 */
class ContactMessageReceived extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public ContactMessage $contactMessage) {}

    public function envelope(): Envelope
    {
        $name = trim($this->contactMessage->first_name.' '.$this->contactMessage->last_name);

        return new Envelope(
            subject: 'New contact message: '.($this->contactMessage->subject ?: 'No subject'),
            // Gmail rejects a From it does not own, so the sender stays the
            // site address and the visitor goes in Reply-To instead.
            replyTo: [new Address($this->contactMessage->email, $name !== '' ? $name : $this->contactMessage->email)],
        );
    }

    public function content(): Content
    {
        return new Content(markdown: 'emails.contact-message');
    }
}
