<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Public Contact Address
    |--------------------------------------------------------------------------
    |
    | The single address shown across the site — contact page, about page,
    | privacy policy and terms. It previously differed on every page
    | (info@, support@, privacy@, legal@), none of which were real mailboxes.
    |
    | The adminjobgader@ account that replaced them was closed by Google, so
    | the address moved again, and again with the rename. Change it here and in
    | SITE_CONTACT_EMAIL on the server; every page reads it from this one key.
    |
    */

    'contact_email' => env('SITE_CONTACT_EMAIL', 'sajaddigitalservices@gmail.com'),

    /*
    |--------------------------------------------------------------------------
    | WhatsApp Number
    |--------------------------------------------------------------------------
    |
    | Digits only, including the country code and no plus sign or spaces —
    | wa.me rejects anything else. Leave it empty and the WhatsApp buttons
    | simply do not render, which is the right behaviour: a contact button
    | that dials a wrong number is worse than no button.
    |
    */

    'whatsapp' => env('SITE_WHATSAPP', '923157033832'),

    /*
    |--------------------------------------------------------------------------
    | Public Phone Number
    |--------------------------------------------------------------------------
    |
    | The same line as the WhatsApp number, written the way a reader expects to
    | see it. The pages link it with tel:, stripping everything but the digits,
    | so the two can never drift apart on the page even though they are stored
    | separately — one has to be dialable, the other readable.
    |
    */

    'phone' => env('SITE_PHONE', '+92 315 703 3832'),

    'phone_local' => env('SITE_PHONE_LOCAL', '0315 703 3832'),

    /*
    |--------------------------------------------------------------------------
    | Public Location
    |--------------------------------------------------------------------------
    |
    | City level only. Every signed memorandum of understanding is with a
    | Lodhran department, which is where this comes from; there is no street
    | address on the site because nobody has given one to publish. Set
    | SITE_ADDRESS on the server the day there is one.
    |
    */

    'address' => env('SITE_ADDRESS', 'Lodhran, Punjab, Pakistan'),

    /*
     * Named only when the listings table cannot answer the question — an empty
     * board still has to render a sentence. SiteCoverage reads the real list
     * from the jobs themselves.
     */
    'fallback_countries' => ['United States', 'United Kingdom', 'Pakistan'],

];
