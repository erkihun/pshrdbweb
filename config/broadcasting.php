<?php

return [
    'default' => env('BROADCAST_CONNECTION', 'log'),

    'connections' => [
        'log' => [
            'driver' => 'log',
        ],

        'null' => [
            'driver' => 'null',
        ],

        'reverb' => [
            'driver' => 'reverb',
            'key' => env('REVERB_APP_KEY'),
            'secret' => env('REVERB_APP_SECRET'),
            'app_id' => env('REVERB_APP_ID'),
            'options' => [
                'scheme' => env('REVERB_SCHEME', 'https'),
                'host' => env('REVERB_HOST', 'reverb.laravel.com'),
                'port' => env('REVERB_PORT', 443),
                'encrypted' => true,
            ],
            'client_options' => [
                'connect_timeout' => 5,
                'timeout' => 5,
            ],
        ],
    ],
];
