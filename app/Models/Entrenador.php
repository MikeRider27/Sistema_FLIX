<?php
declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

final class Entrenador extends Model
{
    public static function listar(): array
    {
        return self::todos(
            'SELECT e.*, (SELECT count(*) FROM membresias m WHERE m.id_entrenador = e.id_entrenador AND m.estado = \'activa\' AND m.fecha_fin >= CURRENT_DATE) AS alumnos
             FROM entrenadores e ORDER BY e.activo DESC, e.apellido, e.nombre'
        );
    }

    public static function encontrar(int $id): ?array
    {
        return self::uno('SELECT * FROM entrenadores WHERE id_entrenador = :id', ['id' => $id]);
    }

    public static function opciones(): array
    {
        $out = [];
        foreach (self::todos('SELECT * FROM entrenadores WHERE activo ORDER BY apellido, nombre') as $e) {
            $out[$e['id_entrenador']] = $e['apellido'] . ', ' . $e['nombre'] . ($e['especialidad'] ? ' — ' . $e['especialidad'] : '');
        }
        return $out;
    }

    public static function ciEnUso(string $ci, ?int $exceptoId = null): bool
    {
        return (bool) self::valor('SELECT 1 FROM entrenadores WHERE ci = :ci AND id_entrenador <> :id', ['ci' => $ci, 'id' => $exceptoId ?? 0]);
    }

    public static function crear(array $d): int
    {
        return self::insertar(
            'INSERT INTO entrenadores (ci, nombre, apellido, telefono, email, especialidad, activo)
             VALUES (:ci, :nombre, :apellido, :telefono, :email, :especialidad, :activo) RETURNING id_entrenador',
            self::params($d)
        );
    }

    public static function actualizar(int $id, array $d): void
    {
        self::ejecutar(
            'UPDATE entrenadores SET ci = :ci, nombre = :nombre, apellido = :apellido, telefono = :telefono,
                    email = :email, especialidad = :especialidad, activo = :activo WHERE id_entrenador = :id',
            self::params($d) + ['id' => $id]
        );
    }

    public static function eliminar(int $id): void
    {
        self::ejecutar('DELETE FROM entrenadores WHERE id_entrenador = :id', ['id' => $id]);
    }

    private static function params(array $d): array
    {
        return [
            'ci'           => $d['ci'],
            'nombre'       => $d['nombre'],
            'apellido'     => $d['apellido'],
            'telefono'     => $d['telefono'],
            'email'        => $d['email'],
            'especialidad' => $d['especialidad'],
            'activo'       => $d['activo'] ? 'true' : 'false',
        ];
    }
}
