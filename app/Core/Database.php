<?php
declare(strict_types=1);

namespace App\Core;

use PDO;

final class Database
{
    private static ?PDO $pdo = null;

    public static function pdo(): PDO
    {
        if (self::$pdo === null) {
            $c = Config::get('db');
            $dsn = sprintf('pgsql:host=%s;port=%s;dbname=%s', $c['host'], $c['port'], $c['name']);
            $pdo = new PDO($dsn, $c['user'], $c['pass'], [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ]);
            // CURRENT_DATE y now() deben respetar la zona horaria de la aplicación
            $pdo->exec('SET TIME ZONE ' . $pdo->quote((string) Config::get('app.tz')));
            self::$pdo = $pdo;
        }
        return self::$pdo;
    }
}
