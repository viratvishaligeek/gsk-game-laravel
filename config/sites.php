<?php

$defaultKey = env('SITE_DEFAULT_KEY', 'gali-satta-kings');

return [
    'default' => $defaultKey,
    'strict_hosts' => env('SITES_STRICT_HOSTS', env('APP_ENV', 'production') === 'production'),
    'current_key' => $defaultKey,
    'current' => [
        'key' => $defaultKey,
        'name' => env('APP_NAME', 'gali satta kings'),
        'url' => rtrim(env('APP_URL', 'https://galisattakings.com'), '/'),
        'hosts' => ['galisattakings.com', 'www.galisattakings.com'],
    ],
    'sites' => [
        'gali-satta-kings' => [
            'name' => 'gali satta kings',
            'url' => rtrim(env('SITE_PLAY_ONLINE_KHAIWAL_URL', env('APP_URL', 'https://galisattakings.com')), '/'),
            'hosts' => ['galisattakings.com', 'www.galisattakings.com'],
        ],
        '1sattaking' => [
            'name' => '1 Satta Kings',
            'url' => rtrim(env('SITE_1SATTAKINGS_URL', 'https://1sattaking.com'), '/'),
            'hosts' => ['1sattaking.com', 'www.1sattaking.com'],
        ],
    ],
];
