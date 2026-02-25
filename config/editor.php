<?php

return [
    /*
    |--------------------------------------------------------------------------
    | TinyMCE Configuration
    |--------------------------------------------------------------------------
    |
    | Store configuration that is specific to the TinyMCE build and upload
    | endpoints, so it can be overridden across environments.
    |
    */
    'api_key' => env('TINYMCE_API_KEY', 'no-api-key'),
    'upload_route' => env('TINYMCE_UPLOAD_ROUTE', 'admin.editor.upload'),
];
