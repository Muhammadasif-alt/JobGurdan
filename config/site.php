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
    | the address moved again. Change it here and in SITE_CONTACT_EMAIL on the
    | server; every page reads it from this one key.
    |
    */

    'contact_email' => env('SITE_CONTACT_EMAIL', 'infojobgader@gmail.com'),

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

    'whatsapp' => env('SITE_WHATSAPP'),

    /*
    |--------------------------------------------------------------------------
    | Owner and Location
    |--------------------------------------------------------------------------
    |
    | Shown on the About and Contact pages only when filled in. Set
    | SITE_OWNER_NAME, SITE_OWNER_ROLE, SITE_OWNER_LOCATION and
    | SITE_OWNER_ADDRESS in .env on the server. A name or address that is not
    | real is worse than none, so nothing is printed until a value exists.
    |
    */

    'owner' => [
        'name' => env('SITE_OWNER_NAME', 'Muhammad Asif'),
        'role' => env('SITE_OWNER_ROLE', 'Founder and editor'),
        'location' => env('SITE_OWNER_LOCATION', 'Pakistan'),
        'address' => env('SITE_OWNER_ADDRESS'),
    ],

    /*
     * The people who run the site, shown on the About page. Add a photo path
     * relative to public/ (for example public/team/ali.jpg) only when the file
     * is a real photo of that person; without one the card shows initials.
     */
    'team' => [
        ['name' => 'Muhammad Asif', 'role' => 'Founder and Editor', 'photo' => 'public/team/muhammad-asif.jpg', 'bio' => 'Muhammad Asif is the founder and editor of JobGader. He publishes the job listings and guides and looks after the site development. He has worked in Pakistan for three years.'],
        ['name' => 'Ali Rehman', 'role' => 'Content Writer', 'photo' => null, 'bio' => 'Ali Rehman writes the career guides and researches job listings for JobGader. He has worked in Pakistan for three years.'],
        ['name' => 'Abdullah Zeheer', 'role' => 'Social Media Manager', 'photo' => null, 'bio' => 'Abdullah Zeheer manages the JobGader posts on LinkedIn, Facebook and Pinterest. He has worked in Pakistan for three years.'],
    ],

    /*
     * Named only when the listings table cannot answer the question — an empty
     * board still has to render a sentence. SiteCoverage reads the real list
     * from the jobs themselves.
     */
    'fallback_countries' => ['United States', 'United Kingdom', 'Pakistan'],

];
