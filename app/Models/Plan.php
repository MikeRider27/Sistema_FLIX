<?php
declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

final class Plan extends Model
{
    public static function listar(): array
    {
        return self::todos('SELECT * FROM planes ORDER BY activo DESC, duracion_dias');
    }

    public static function encontrar(int $id): ?array
    {
        return self::uno('SELECT * FROM planes WHERE id_plan = :id', ['id' => $id]);
    }

    /** Solo activos: id => "Nombre — Gs. X (N días)". */
    public static function opciones(): array
    {
        $out = [];
        foreach (self::todos('SELECT * FROM planes WHERE activo ORDER BY duracion_dias') as $p) {
            $out[$p['id_plan']] = $p['nombre'] . ' — ' . dinero($p['precio']) . ' (' . $p['duracion_dias'] . ' días)';
        }
        return $out;
    }

    public static function nombreEnUso(string $nombre, ?int $exceptoId = null): bool
    {
        return (bool) self::valor('SELECT 1 FROM planes WHERE lower(nombre) = lower(:n) AND id_plan <> :id', ['n' => $nombre, 'id' => $exceptoId ?? 0]);
    }

    public static function crear(array $d): int
    {
        return self::insertar(
            'INSERT INTO planes (nombre, descripcion, duracion_dias, precio, activo)
             VALUES (:nombre, :descripcion, :duracion_dias, :precio, :activo) RETURNING id_plan',
            self::params($d)
        );
    }

    public static function actualizar(int $id, array $d): void
    {
        self::ejecutar(
            'UPDATE planes SET nombre = :nombre, descripcion = :descripcion, duracion_dias = :duracion_dias,
                    precio = :precio, activo = :activo WHERE id_plan = :id',
            self::params($d) + ['id' => $id]
        );
    }

    public static function eliminar(int $id): void
    {
        self::ejecutar('DELETE FROM planes WHERE id_plan = :id', ['id' => $id]);
    }

    private static function params(array $d): array
    {
        return [
            'nombre'        => $d['nombre'],
            'descripcion'   => $d['descripcion'],
            'duracion_dias' => (int) $d['duracion_dias'],
            'precio'        => $d['precio'],
            'activo'        => $d['activo'] ? 'true' : 'false',
        ];
    }
}
