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

it('renders the notification without a missing view path', function () {
    $message = ContactMessage::create([
        'first_name' => 'Sara',
        'last_name' => 'Iqbal',
        'email' => 'sara@example.com',
        'subject' => 'Cleaner roles in London',
        'message' => 'Are the London cleaning listings still live?',
    ]);

    // Mail::fake() never renders, so a broken view slips past assertSent —
    // which is exactly how a markdown mailable declared with view() reached
    // production and failed with "No hint path defined for [mail]".
    $rendered = (new ContactMessageReceived($message))->render();

    expect($rendered)->toContain('Cleaner roles in London')
        ->toContain('sara@example.com');
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
