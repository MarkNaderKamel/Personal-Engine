<?php

return [
    'driver' => 'pgsql',
    'host' => getenv('PGHOST'),
    'port' => getenv('PGPORT'),
    'database' => getenv('PGDATABASE'),
    'username' => getenv('PGUSER'),
    'password' => getenv('PGPASSWORD'),
    'charset' => 'utf8',
    'options' => [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]
];
