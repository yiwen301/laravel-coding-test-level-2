<?php

declare(strict_types=1);

return [
    'auth' => [
        'password_hash_key'       => env('PASSWORD_HASH_KEY', ''),
        'authorization_token_key' => env('AUTHORIZATION_TOKEN_KEY', '')
    ],
];
