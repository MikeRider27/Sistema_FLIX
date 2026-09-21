<?php
declare(strict_types=1);

namespace App\Core;

final class Config
{
    private static array $datos = [];

    public static function cargar(array $datos): void
    {
        self::$datos = $datos;
    }

    /** Acceso con notación de puntos: Config::get('db.host') */
    public static function get(string $clave, mixed $def = null): mixed
    {
        $v = self::$datos;
        foreach (explode('.', $clave) as $parte) {
            if (!is_array($v) || !array_key_exists($parte, $v)) {
                return $def;
            }
            $v = $v[$parte];
        }
        return $v;
    }
}
