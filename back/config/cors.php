<?php

return [
    'paths' => ['api/*', 'sanctum/csrf-cookie', 'broadcasting/auth'],

    'allowed_methods' => ['*'],

    'allowed_origins' => [
        'https://payzen.raynis.co',
        'http://localhost:5173',
    ],

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],  // Important : autoriser tous les en-têtes

    'exposed_headers' => [],

    'max_age' => 86400,  // Cache la réponse preflight pour 24h

    'supports_credentials' => true,
];
