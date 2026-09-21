<?php
declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

final class Usuario extends Model
{
    public static function porNombreUsuario(string $usuario): ?array
    {
        return self::uno('SELECT * FROM usuarios WHERE lower(usuario) = lower(:u)', ['u' => $usuario]);
    }

    public static function listar(): array
    {
        return self::todos('SELECT id_usuario, nombre, usuario, rol, activo, creado_en FROM usuarios ORDER BY nombre');
    }

    public static function encontrar(int $id): ?array
    {
        return self::uno('SELECT id_usuario, nombre, usuario, rol, activo FROM usuarios WHERE id_usuario = :id', ['id' => $id]);
    }

    public static function usuarioEnUso(string $usuario, ?int $exceptoId = null): bool
    {
        return (bool) self::valor(
            'SELECT 1 FROM usuarios WHERE lower(usuario) = lower(:u) AND id_usuario <> :id',
            ['u' => $usuario, 'id' => $exceptoId ?? 0]
        );
    }

    public static function crear(array $d, string $clave): int
    {
        return self::insertar(
            'INSERT INTO usuarios (nombre, usuario, clave_hash, rol, activo)
             VALUES (:nombre, lower(:usuario), :hash, :rol, :activo) RETURNING id_usuario',
            [
                'nombre'  => $d['nombre'],
                'usuario' => $d['usuario'],
                'hash'    => password_hash($clave, PASSWORD_DEFAULT),
                'rol'     => $d['rol'],
                'activo'  => $d['activo'] ? 'true' : 'false',
            ]
        );
    }

    public static function actualizar(int $id, array $d): void
    {
        self::ejecutar(
            'UPDATE usuarios SET nombre = :nombre, rol = :rol, activo = :activo WHERE id_usuario = :id',
            ['nombre' => $d['nombre'], 'rol' => $d['rol'], 'activo' => $d['activo'] ? 'true' : 'false', 'id' => $id]
        );
    }

    public static function cambiarClave(int $id, string $clave): void
    {
        self::ejecutar(
            'UPDATE usuarios SET clave_hash = :h WHERE id_usuario = :id',
            ['h' => password_hash($clave, PASSWORD_DEFAULT), 'id' => $id]
        );
    }

    public static function contarAdminsActivos(): int
    {
        return (int) self::valor("SELECT count(*) FROM usuarios WHERE rol = 'admin' AND activo");
    }
}
