<?php

declare(strict_types=1);

return [
    /*
    |--------------------------------------------------------------------------
    | JSONPlaceholder API Base URL
    |--------------------------------------------------------------------------
    |
    | The base URL for the JSONPlaceholder API. This can be overridden in
    | your .env file using the PLACEHOLDER_API_BASE_URL variable.
    |
    */
    'base_url' => env('PLACEHOLDER_API_BASE_URL', 'https://jsonplaceholder.typicode.com'),
];
