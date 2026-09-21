<?php
declare(strict_types=1);

namespace App\Core;

final class Response
{
    public static function redirigir(string $ruta): never
    {
        header('Location: ' . $ruta);
        exit;
    }
}
