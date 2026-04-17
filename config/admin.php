<?php

return [
    'seed' => [
        'name' => env('ADMIN_NAME', 'Admin'),
        'email' => env('ADMIN_EMAIL'),
        'password' => env('ADMIN_PASSWORD'),
        'status' => env('ADMIN_STATUS', 'active'),
    ],
];

