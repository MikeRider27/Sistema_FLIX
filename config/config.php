<?php
declare(strict_types=1);

$env = static fn(string $k, string $def = ''): string => getenv($k) !== false ? (string) getenv($k) : $def;

return [
    'app' => [
        'nombre' => $env('APP_NAME', 'Sistema FLIX'),
        'debug'  => filter_var($env('APP_DEBUG', 'false'), FILTER_VALIDATE_BOOLEAN),
        'tz'     => $env('APP_TZ', 'America/Asuncion'),
        'moneda' => 'Gs.',
    ],
    'db' => [
        'host' => $env('DB_HOST', 'db'),
        'port' => $env('DB_PORT', '5432'),
        'name' => $env('DB_NAME', 'flix'),
        'user' => $env('DB_USER', 'flix'),
        'pass' => $env('DB_PASS'),
    ],
];
