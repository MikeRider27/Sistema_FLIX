<?php
declare(strict_types=1);

namespace App\Core;

use PDO;

/** Utilidades de consulta con sentencias preparadas para los modelos. */
abstract class Model
{
    protected static function db(): PDO
    {
        return Database::pdo();
    }

    protected static function todos(string $sql, array $params = []): array
    {
        $st = self::db()->prepare($sql);
        $st->execute($params);
        return $st->fetchAll();
    }

    protected static function uno(string $sql, array $params = []): ?array
    {
        $st = self::db()->prepare($sql);
        $st->execute($params);
        $fila = $st->fetch();
        return $fila === false ? null : $fila;
    }

    protected static function valor(string $sql, array $params = []): mixed
    {
        $st = self::db()->prepare($sql);
        $st->execute($params);
        return $st->fetchColumn();
    }

    /** Ejecuta INSERT/UPDATE/DELETE y devuelve las filas afectadas. */
    protected static function ejecutar(string $sql, array $params = []): int
    {
        $st = self::db()->prepare($sql);
        $st->execute($params);
        return $st->rowCount();
    }

    /** INSERT ... RETURNING <pk>: devuelve el id generado. */
    protected static function insertar(string $sql, array $params = []): int
    {
        return (int) self::valor($sql, $params);
    }
}
