<?php

$defaultKey = env('SITE_DEFAULT_KEY', 'galisattakings');

return [
    'default' => $defaultKey,
    'strict_hosts' => env('SITES_STRICT_HOSTS', env('APP_ENV', 'production') === 'production'),
    'current_key' => $defaultKey,
    'current' => [
        'key' => $defaultKey,
        'name' => env('APP_NAME', 'Gali Satta Kings'),
        'url' => rtrim(env('APP_URL', 'https://galisattakings.com.com'), '/'),
        'hosts' => ['galisattakings.com.com', 'www.galisattakings.com.com'],
    ],
    'sites' => [
        'galisattakings' => [
            'name' => 'Gali Satta Kings',
            'url' => rtrim(env('SITE_PLAY_ONLINE_KHAIWAL_URL', env('APP_URL', 'https://galisattakings.com.com')), '/'),
            'hosts' => ['galisattakings.com.com', 'www.galisattakings.com.com'],
        ],
        '1sattaking' => [
            'name' => '1 Satta King',
            'url' => rtrim('https://1sattaking.com', '/'),
            'hosts' => ['1sattaking.com', 'www.1sattaking.com'],
        ],
    ],
];
