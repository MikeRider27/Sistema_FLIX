<?php
declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

final class Socio extends Model
{
    public static function listar(?string $q = null, ?string $estado = null): array
    {
        $sql = 'SELECT s.*,
                       (SELECT max(fecha_fin) FROM membresias m WHERE m.id_socio = s.id_socio AND m.estado = \'activa\') AS vence
                FROM socios s WHERE 1 = 1';
        $p = [];
        if ($q !== null && $q !== '') {
            $sql .= ' AND (s.ci ILIKE :q OR s.nombre ILIKE :q OR s.apellido ILIKE :q OR (s.nombre || \' \' || s.apellido) ILIKE :q)';
            $p['q'] = '%' . $q . '%';
        }
        if ($estado !== null && $estado !== '') {
            $sql .= ' AND s.estado = :estado';
            $p['estado'] = $estado;
        }
        return self::todos($sql . ' ORDER BY s.apellido, s.nombre', $p);
    }

    public static function contarActivos(): int
    {
        return (int) self::valor("SELECT count(*) FROM socios WHERE estado = 'activo'");
    }

    public static function encontrar(int $id): ?array
    {
        return self::uno('SELECT * FROM socios WHERE id_socio = :id', ['id' => $id]);
    }

    public static function porCi(string $ci): ?array
    {
        return self::uno('SELECT * FROM socios WHERE ci = :ci', ['ci' => $ci]);
    }

    public static function ciEnUso(string $ci, ?int $exceptoId = null): bool
    {
        return (bool) self::valor('SELECT 1 FROM socios WHERE ci = :ci AND id_socio <> :id', ['ci' => $ci, 'id' => $exceptoId ?? 0]);
    }

    /** Socios activos para selectores: id => "Apellido, Nombre (CI)". */
    public static function opciones(): array
    {
        $out = [];
        foreach (self::todos("SELECT id_socio, apellido, nombre, ci FROM socios WHERE estado = 'activo' ORDER BY apellido, nombre") as $s) {
            $out[$s['id_socio']] = $s['apellido'] . ', ' . $s['nombre'] . ' (' . $s['ci'] . ')';
        }
        return $out;
    }

    private const CAMPOS = ['ci', 'nombre', 'apellido', 'fecha_nacimiento', 'sexo', 'telefono', 'email', 'direccion', 'contacto_emergencia', 'notas', 'estado'];

    public static function crear(array $d): int
    {
        return self::insertar(
            'INSERT INTO socios (ci, nombre, apellido, fecha_nacimiento, sexo, telefono, email, direccion, contacto_emergencia, notas, estado)
             VALUES (:ci, :nombre, :apellido, :fecha_nacimiento, :sexo, :telefono, :email, :direccion, :contacto_emergencia, :notas, :estado)
             RETURNING id_socio',
            array_intersect_key($d, array_flip(self::CAMPOS))
        );
    }

    public static function actualizar(int $id, array $d): void
    {
        self::ejecutar(
            'UPDATE socios SET ci = :ci, nombre = :nombre, apellido = :apellido, fecha_nacimiento = :fecha_nacimiento,
                    sexo = :sexo, telefono = :telefono, email = :email, direccion = :direccion,
                    contacto_emergencia = :contacto_emergencia, notas = :notas, estado = :estado
             WHERE id_socio = :id',
            array_intersect_key($d, array_flip(self::CAMPOS)) + ['id' => $id]
        );
    }

    public static function eliminar(int $id): void
    {
        self::ejecutar('DELETE FROM socios WHERE id_socio = :id', ['id' => $id]);
    }
}
