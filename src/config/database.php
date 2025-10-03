<?php

return [
    'host' => getenv('DB_HOST') ?: 'mysql',
    'database' => getenv('DB_DATABASE') ?: 'laravel',
    'username' => getenv('DB_USERNAME') ?: 'laravel',
    'password' => getenv('DB_PASSWORD') ?: 'secret',
];
