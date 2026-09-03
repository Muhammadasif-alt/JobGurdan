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
    */

    'contact_email' => env('SITE_CONTACT_EMAIL', 'adminjobgader@gmail.com'),

    /*
     * Named only when the listings table cannot answer the question — an empty
     * board still has to render a sentence. SiteCoverage reads the real list
     * from the jobs themselves.
     */
    'fallback_countries' => ['United States', 'United Kingdom', 'Pakistan'],

];
