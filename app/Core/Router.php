<?php
declare(strict_types=1);

namespace App\Core;

final class Router
{
    /** @var array<int, array{metodo:string, patron:string, handler:array, acceso:mixed}> */
    private array $rutas = [];

    /**
     * $acceso: 'publico' | 'auth' (cualquier usuario logueado) | ['admin', ...] (roles permitidos)
     */
    public function get(string $ruta, array $handler, mixed $acceso = 'auth'): void
    {
        $this->agregar('GET', $ruta, $handler, $acceso);
    }

    public function post(string $ruta, array $handler, mixed $acceso = 'auth'): void
    {
        $this->agregar('POST', $ruta, $handler, $acceso);
    }

    private function agregar(string $metodo, string $ruta, array $handler, mixed $acceso): void
    {
        $patron = '#^' . preg_replace('#\{(\w+)\}#', '(?P<$1>\d+)', $ruta) . '$#';
        $this->rutas[] = compact('metodo', 'patron', 'handler', 'acceso');
    }

    public function dispatch(string $metodo, string $uri): void
    {
        $uri = '/' . trim($uri, '/');
        $rutaExiste = false;

        foreach ($this->rutas as $r) {
            if (!preg_match($r['patron'], $uri, $m)) {
                continue;
            }
            $rutaExiste = true;
            if ($r['metodo'] !== $metodo) {
                continue;
            }

            try {
                $this->autorizar($r['acceso']);
                if ($metodo === 'POST') {
                    Csrf::verificar();
                }
                [$clase, $accion] = $r['handler'];
                $params = array_map('intval', array_filter($m, 'is_string', ARRAY_FILTER_USE_KEY));
                (new $clase())->$accion(...array_values($params));
            } catch (HttpException $e) {
                $this->error($e->getCode(), $e->getMessage());
            } catch (\Throwable $e) {
                error_log((string) $e);
                $this->error(500, Config::get('app.debug') ? $e->getMessage() : 'Error interno del servidor');
            }
            return;
        }

        $this->error($rutaExiste ? 405 : 404);
    }

    private function autorizar(mixed $acceso): void
    {
        if ($acceso === 'publico') {
            return;
        }
        if (!Auth::check()) {
            Flash::guardarDestino();
            Response::redirigir('/login');
        }
        if (is_array($acceso) && !Auth::tieneRol(...$acceso)) {
            throw new HttpException('No tenés permiso para acceder a esta sección', 403);
        }
    }

    private function error(int $codigo, string $mensaje = ''): void
    {
        http_response_code($codigo);
        $titulos = [403 => 'Acceso denegado', 404 => 'Página no encontrada', 405 => 'Método no permitido', 500 => 'Error del servidor'];
        View::render('errores/error', [
            'titulo'  => $titulos[$codigo] ?? 'Error',
            'codigo'  => $codigo,
            'mensaje' => $mensaje,
        ], Auth::check() ? 'main' : 'simple');
    }
}
