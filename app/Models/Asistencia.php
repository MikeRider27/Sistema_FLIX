<?php
declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

final class Asistencia extends Model
{
    public static function delDia(string $fecha): array
    {
        return self::todos(
            "SELECT a.*, s.nombre || ' ' || s.apellido AS socio, s.ci
             FROM asistencias a JOIN socios s ON s.id_socio = a.id_socio
             WHERE a.entrada::date = :f::date ORDER BY a.entrada DESC",
            ['f' => $fecha]
        );
    }

    public static function porSocio(int $idSocio, int $limite = 20): array
    {
        return self::todos(
            'SELECT * FROM asistencias WHERE id_socio = :id ORDER BY entrada DESC LIMIT ' . (int) $limite,
            ['id' => $idSocio]
        );
    }

    public static function ultimas(int $limite = 8): array
    {
        return self::todos(
            "SELECT a.entrada, s.nombre || ' ' || s.apellido AS socio, s.ci
             FROM asistencias a JOIN socios s ON s.id_socio = a.id_socio
             ORDER BY a.entrada DESC LIMIT " . (int) $limite
        );
    }

    public static function yaIngresoHoy(int $idSocio): bool
    {
        return (bool) self::valor('SELECT 1 FROM asistencias WHERE id_socio = :id AND entrada::date = CURRENT_DATE', ['id' => $idSocio]);
    }

    public static function registrar(int $idSocio, ?int $idUsuario): void
    {
        self::ejecutar('INSERT INTO asistencias (id_socio, id_usuario) VALUES (:s, :u)', ['s' => $idSocio, 'u' => $idUsuario]);
    }

    public static function contarHoy(): int
    {
        return (int) self::valor('SELECT count(*) FROM asistencias WHERE entrada::date = CURRENT_DATE');
    }
}
