<?php
declare(strict_types=1);

require dirname(__DIR__) . '/app/bootstrap.php';

$router = require BASE_PATH . '/app/routes.php';
$router->dispatch($_SERVER['REQUEST_METHOD'], parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?: '/');
