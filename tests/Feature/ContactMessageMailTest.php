<?php

use App\Mail\ContactMessageReceived;
use App\Models\ContactMessage;
use Illuminate\Support\Facades\Mail;

use function Pest\Laravel\post;

beforeEach(function () {
    Mail::fake();
});

it('emails the site address when someone submits the contact form', function () {
    post(route('contact.store'), [
        'first_name' => 'Amina',
        'last_name' => 'Khan',
        'email' => 'amina@example.com',
        'subject' => 'Question about a listing',
        'message' => 'Is the warehouse role in Birmingham still open? I have the right to work already.',
    ])->assertRedirect('/');

    expect(ContactMessage::count())->toBe(1);

    Mail::assertSent(ContactMessageReceived::class, function (ContactMessageReceived $mail): bool {
        // Replying goes to the visitor, while the envelope sender stays ours —
        // Gmail refuses a From address it does not own.
        return $mail->hasTo(config('site.contact_email'))
            && $mail->hasReplyTo('amina@example.com');
    });
});

it('still keeps the message when the mail transport fails', function () {
    Mail::shouldReceive('to')->andThrow(new RuntimeException('SMTP down'));

    post(route('contact.store'), [
        'first_name' => 'Bilal',
        'last_name' => 'Ahmed',
        'email' => 'bilal@example.com',
        'subject' => 'Driver jobs',
        'message' => 'Are the Saudi driver listings still accepting applications this month?',
    ])->assertRedirect('/');

    expect(ContactMessage::count())->toBe(1);
});
