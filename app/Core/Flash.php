<?php
declare(strict_types=1);

namespace App\Core;

/** Mensajes, errores de validación y datos previos que sobreviven una sola redirección. */
final class Flash
{
    private static array $mensaje = [];
    private static array $errores = [];
    private static array $old = [];

    /** Mueve lo guardado en sesión a memoria, para que se consuma una sola vez. */
    public static function iniciar(): void
    {
        self::$mensaje = $_SESSION['_flash']  ?? [];
        self::$errores = $_SESSION['_errores'] ?? [];
        self::$old     = $_SESSION['_old']     ?? [];
        unset($_SESSION['_flash'], $_SESSION['_errores'], $_SESSION['_old']);
    }

    public static function set(string $tipo, string $texto): void
    {
        $_SESSION['_flash'] = ['tipo' => $tipo, 'texto' => $texto];
    }

    public static function mensaje(): array
    {
        return self::$mensaje;
    }

    public static function conEntrada(array $errores, array $old): void
    {
        $_SESSION['_errores'] = $errores;
        $_SESSION['_old'] = $old;
    }

    public static function error(string $campo): ?string
    {
        return self::$errores[$campo] ?? null;
    }

    public static function hayErrores(): bool
    {
        return self::$errores !== [];
    }

    public static function old(string $campo, mixed $def = null): mixed
    {
        return array_key_exists($campo, self::$old) ? self::$old[$campo] : $def;
    }

    public static function guardarDestino(): void
    {
        if (($_SERVER['REQUEST_METHOD'] ?? '') === 'GET') {
            $_SESSION['_destino'] = $_SERVER['REQUEST_URI'] ?? '/';
        }
    }

    public static function tomarDestino(): string
    {
        $d = $_SESSION['_destino'] ?? '/';
        unset($_SESSION['_destino']);
        // Solo rutas internas (evita open redirect)
        return is_string($d) && preg_match('#^/(?!/)#', $d) ? $d : '/';
    }
}
