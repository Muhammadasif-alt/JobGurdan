@php
    $name = trim($contactMessage->first_name.' '.$contactMessage->last_name);
@endphp
<x-mail::message>
# New contact message

**From:** {{ $name !== '' ? $name : 'Not given' }}
**Email:** {{ $contactMessage->email }}
**Subject:** {{ $contactMessage->subject ?: 'Not given' }}
**Received:** {{ $contactMessage->created_at?->format('j F Y, g:ia') }}

---

{{ $contactMessage->message }}

---

<x-mail::button :url="route('admin.contact-messages.show', $contactMessage)">
Open in the admin panel
</x-mail::button>

Reply to this email to answer {{ $contactMessage->email }} directly.

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
