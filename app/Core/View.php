<?php
declare(strict_types=1);

namespace App\Core;

final class View
{
    /** Renderiza app/Views/<vista>.php dentro del layout indicado. */
    public static function render(string $vista, array $datos = [], string $layout = 'main'): void
    {
        $contenido = self::capturar($vista, $datos);
        $titulo = $datos['titulo'] ?? Config::get('app.nombre');
        require BASE_PATH . '/app/Views/layouts/' . $layout . '.php';
    }

    private static function capturar(string $vista, array $datos): string
    {
        extract($datos, EXTR_SKIP);
        ob_start();
        require BASE_PATH . '/app/Views/' . $vista . '.php';
        return (string) ob_get_clean();
    }
}
