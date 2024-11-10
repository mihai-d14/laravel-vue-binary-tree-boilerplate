<?php

return [
    'default' => env('CACHE_DRIVER', 'array'),

    'stores' => [
        'array' => [
            'driver' => 'array',
            'serialize' => false,
        ],
        'file' => [
            'driver' => 'file',
            'path' => storage_path('framework/cache/data'),
        ],
    ],

    'prefix' => env('CACHE_PREFIX', 'laravel_cache'),
];