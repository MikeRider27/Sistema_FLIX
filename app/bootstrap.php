<?php
declare(strict_types=1);

define('BASE_PATH', dirname(__DIR__));

spl_autoload_register(static function (string $clase): void {
    if (strncmp($clase, 'App\\', 4) !== 0) {
        return;
    }
    $archivo = BASE_PATH . '/app/' . str_replace('\\', '/', substr($clase, 4)) . '.php';
    if (is_file($archivo)) {
        require $archivo;
    }
});

require BASE_PATH . '/app/helpers.php';

use App\Core\Config;
use App\Core\Flash;

Config::cargar(require BASE_PATH . '/config/config.php');
date_default_timezone_set(Config::get('app.tz'));

if (Config::get('app.debug')) {
    ini_set('display_errors', '1');
    error_reporting(E_ALL);
}

session_name('flix_sid');
session_start();
Flash::iniciar();
