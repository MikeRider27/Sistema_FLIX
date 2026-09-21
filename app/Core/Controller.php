<?php
declare(strict_types=1);

namespace App\Core;

abstract class Controller
{
    protected function vista(string $vista, array $datos = []): void
    {
        View::render($vista, $datos);
    }

    protected function redirigir(string $ruta, ?string $tipo = null, ?string $texto = null): never
    {
        if ($tipo !== null) {
            Flash::set($tipo, (string) $texto);
        }
        Response::redirigir($ruta);
    }

    /** Vuelve al formulario conservando lo escrito y mostrando los errores. */
    protected function volverConErrores(string $ruta, array $errores, array $old): never
    {
        Flash::conEntrada($errores, $old);
        Response::redirigir($ruta);
    }

    protected function abortar(int $codigo, string $mensaje = ''): never
    {
        throw new HttpException($mensaje, $codigo);
    }

    /**
     * Toma solo los campos indicados del POST, recortados; "" pasa a null.
     * @param string[] $campos
     */
    protected function entrada(array $campos): array
    {
        $out = [];
        foreach ($campos as $c) {
            $v = $_POST[$c] ?? null;
            $v = is_string($v) ? trim($v) : null;
            $out[$c] = $v === '' ? null : $v;
        }
        return $out;
    }

    /** Guarda cambios en checkbox: 't' si está marcado. */
    protected function check(string $campo): bool
    {
        return isset($_POST[$campo]) && $_POST[$campo] === '1';
    }
}
