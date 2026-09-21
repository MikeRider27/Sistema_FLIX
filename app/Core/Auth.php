<?php
declare(strict_types=1);

namespace App\Core;

use App\Models\Usuario;

final class Auth
{
    public static function intentar(string $usuario, string $clave): bool
    {
        $u = Usuario::porNombreUsuario($usuario);
        $ok = $u !== null && $u['activo'] && password_verify($clave, $u['clave_hash']);

        if (!$ok) {
            usleep(300000); // frena la fuerza bruta más burda
            return false;
        }

        if (password_needs_rehash($u['clave_hash'], PASSWORD_DEFAULT)) {
            Usuario::cambiarClave((int) $u['id_usuario'], $clave);
        }

        session_regenerate_id(true);
        $_SESSION['usuario'] = [
            'id'      => (int) $u['id_usuario'],
            'nombre'  => $u['nombre'],
            'usuario' => $u['usuario'],
            'rol'     => $u['rol'],
        ];
        return true;
    }

    public static function usuario(): ?array
    {
        return $_SESSION['usuario'] ?? null;
    }

    public static function id(): ?int
    {
        return self::usuario()['id'] ?? null;
    }

    public static function check(): bool
    {
        return self::usuario() !== null;
    }

    public static function tieneRol(string ...$roles): bool
    {
        $u = self::usuario();
        return $u !== null && in_array($u['rol'], $roles, true);
    }

    public static function salir(): void
    {
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $p = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $p['path'], $p['domain'], $p['secure'], $p['httponly']);
        }
        session_destroy();
    }
}
