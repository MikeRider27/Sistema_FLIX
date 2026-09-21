<?php
declare(strict_types=1);

namespace App\Core;

final class Csrf
{
    public static function token(): string
    {
        if (empty($_SESSION['_csrf'])) {
            $_SESSION['_csrf'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['_csrf'];
    }

    public static function campo(): string
    {
        return '<input type="hidden" name="_csrf" value="' . self::token() . '">';
    }

    public static function verificar(): void
    {
        $enviado = $_POST['_csrf'] ?? '';
        if (!is_string($enviado) || !hash_equals(self::token(), $enviado)) {
            throw new HttpException('El formulario expiró o es inválido. Volvé a intentarlo.', 419);
        }
    }
}
